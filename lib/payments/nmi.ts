import "server-only";

import { PRODUCT } from "./catalog";
import type { OrderInput } from "./orders";
import { siteUrl } from "./stripe";

const DEFAULT_GATEWAY_URL = "https://midkingzllc.transactiongateway.com/api/v2/three-step";

function xmlEscape(value: unknown): string {
  return String(value ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&apos;");
}

function nmiApiKey(): string {
  const key = process.env.NMI_API_KEY?.trim();
  if (!key) {
    throw new Error("NMI_API_KEY is not configured");
  }
  return key;
}

function nmiGatewayUrl(): string {
  return (process.env.NMI_GATEWAY_URL?.trim() || DEFAULT_GATEWAY_URL).replace(/\/$/, "");
}

function gatewayCandidates(): string[] {
  return [
    ...new Set(
      [
        nmiGatewayUrl(),
        DEFAULT_GATEWAY_URL,
        "https://secure.nmi.com/api/v2/three-step",
      ].map((url) => url.replace(/\/$/, "")),
    ),
  ];
}

let lastWorkingGateway: string | null = null;

async function postGatewayXml(xml: string): Promise<string> {
  const urls = lastWorkingGateway
    ? [lastWorkingGateway, ...gatewayCandidates().filter((url) => url !== lastWorkingGateway)]
    : gatewayCandidates();

  let lastText = "";
  for (const url of urls) {
    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "text/xml" },
      body: xml,
    });
    lastText = await response.text();
    if (/Specified API key not found/i.test(lastText)) {
      continue;
    }
    if (/<form-url>/.test(lastText) || /<result>[123]<\/result>/.test(lastText)) {
      lastWorkingGateway = url;
      return lastText;
    }
  }
  return lastText;
}

function yyyymmdd(date = new Date()): string {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");
  return `${year}${month}${day}`;
}

function xmlTag(name: string, value: unknown): string {
  return `<${name}>${xmlEscape(value)}</${name}>`;
}

function customerXml(orderData: OrderInput): string {
  return `
  <billing>
    ${xmlTag("first-name", orderData.firstName)}
    ${xmlTag("last-name", orderData.lastName)}
    ${xmlTag("address1", orderData.address)}
    ${xmlTag("city", orderData.city)}
    ${xmlTag("state", orderData.state)}
    ${xmlTag("postal", orderData.zip)}
    ${xmlTag("country", "US")}
    ${xmlTag("email", orderData.email)}
    ${xmlTag("phone", orderData.phone)}
  </billing>
  <shipping>
    ${xmlTag("first-name", orderData.firstName)}
    ${xmlTag("last-name", orderData.lastName)}
    ${xmlTag("address1", orderData.address)}
    ${xmlTag("city", orderData.city)}
    ${xmlTag("state", orderData.state)}
    ${xmlTag("postal", orderData.zip)}
    ${xmlTag("country", "US")}
  </shipping>`;
}

export function isAllowedNmiFormUrl(url: string): boolean {
  try {
    const parsed = new URL(url);
    if (parsed.protocol !== "https:") return false;
    const host = parsed.hostname.toLowerCase();
    return (
      host === "secure.nmi.com" ||
      host === "secure.networkmerchants.com" ||
      host.endsWith(".nmi.com") ||
      host.endsWith(".transactiongateway.com")
    );
  } catch {
    return false;
  }
}

export async function createNmiCheckout(
  orderId: string,
  amountCents: number,
  orderData: OrderInput,
  ip: string,
) {
  const amount = (amountCents / 100).toFixed(2);
  const redirectUrl = `${siteUrl()}/api/webhooks/nmi`;
  const startDate = yyyymmdd();
  const dayOfMonth = String(new Date().getDate());

  const xml =
    orderData.plan === "monthly"
      ? `<?xml version="1.0" encoding="UTF-8"?>
<add-subscription>
  ${xmlTag("api-key", nmiApiKey())}
  ${xmlTag("redirect-url", redirectUrl)}
  ${xmlTag("start-date", startDate)}
  ${xmlTag("ip-address", ip)}
  ${xmlTag("order-id", orderId)}
  ${xmlTag("order-description", `${PRODUCT.name} monthly`)}
  ${xmlTag("currency", "USD")}
  <plan>
    ${xmlTag("payments", "0")}
    ${xmlTag("amount", amount)}
    ${xmlTag("month-frequency", "1")}
    ${xmlTag("day-of-month", dayOfMonth)}
  </plan>
  ${customerXml(orderData)}
</add-subscription>`
      : `<?xml version="1.0" encoding="UTF-8"?>
<sale>
  ${xmlTag("api-key", nmiApiKey())}
  ${xmlTag("redirect-url", redirectUrl)}
  ${xmlTag("amount", amount)}
  ${xmlTag("ip-address", ip)}
  ${xmlTag("industry", "ecommerce")}
  ${xmlTag("order-id", orderId)}
  ${xmlTag("order-description", PRODUCT.name)}
  ${xmlTag("customer-id", orderData.email)}
  ${customerXml(orderData)}
</sale>`;

  const responseText = await postGatewayXml(xml);
  const formUrlMatch = responseText.match(/<form-url>(.*?)<\/form-url>/);
  if (!formUrlMatch) {
    throw new Error(`NMI init failed: ${responseText}`);
  }

  return formUrlMatch[1];
}

export async function completeNmiCheckout(tokenId: string) {
  const xml = `<?xml version="1.0" encoding="UTF-8"?>
<complete-action>
  ${xmlTag("api-key", nmiApiKey())}
  ${xmlTag("token-id", tokenId)}
</complete-action>`;

  const responseText = await postGatewayXml(xml);

  const resultMatch = responseText.match(/<result>(.*?)<\/result>/);
  const transactionIdMatch = responseText.match(/<transaction-id>(.*?)<\/transaction-id>/);
  const subscriptionIdMatch = responseText.match(/<subscription-id>(.*?)<\/subscription-id>/);
  const resultTextMatch = responseText.match(/<result-text>(.*?)<\/result-text>/);
  const orderIdMatch = responseText.match(/<order-id>(.*?)<\/order-id>/);

  return {
    success: Boolean(resultMatch && resultMatch[1] === "1"),
    transactionId: transactionIdMatch ? transactionIdMatch[1] : null,
    subscriptionId: subscriptionIdMatch ? subscriptionIdMatch[1] : null,
    orderId: orderIdMatch ? orderIdMatch[1] : null,
    resultText: resultTextMatch ? resultTextMatch[1] : "Unknown",
    raw: responseText,
  };
}

function transactCandidates(): string[] {
  return gatewayCandidates().map((url) => url.replace(/\/api\/v2\/three-step$/, "/api/transact.php"));
}

function parseTransact(body: string): { ok: boolean; text: string; transactionId: string | null } {
  const params = new URLSearchParams(body.replace(/\n/g, "&"));
  const response = params.get("response") || params.get("result") || "";
  const text = params.get("responsetext") || params.get("response_code") || body.slice(0, 160);
  return {
    ok: response === "1",
    text,
    transactionId: params.get("transactionid"),
  };
}

async function postTransact(fields: Record<string, string>) {
  const body = new URLSearchParams({ security_key: nmiApiKey(), ...fields }).toString();
  let last = "";
  for (const url of transactCandidates()) {
    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body,
    });
    last = await response.text();
    if (/Specified API key not found/i.test(last)) continue;
    return parseTransact(last);
  }
  return parseTransact(last);
}

export async function refundNmiTransaction(transactionId: string) {
  const voided = await postTransact({ type: "void", transactionid: transactionId });
  if (voided.ok) {
    return { ok: true, method: "void" as const, text: voided.text, transactionId: voided.transactionId };
  }
  const refunded = await postTransact({
    type: "refund",
    transactionid: transactionId,
    amount: "0.00",
  });
  return {
    ok: refunded.ok,
    method: "refund" as const,
    text: refunded.text || voided.text,
    transactionId: refunded.transactionId,
  };
}

export async function cancelNmiSubscription(subscriptionId: string) {
  const deleted = await postTransact({
    type: "delete_subscription",
    subscription_id: subscriptionId,
  });
  if (deleted.ok) return deleted;

  const xml = `<?xml version="1.0" encoding="UTF-8"?>
<delete-subscription>
  ${xmlTag("api-key", nmiApiKey())}
  ${xmlTag("subscription-id", subscriptionId)}
</delete-subscription>`;
  const responseText = await postGatewayXml(xml);
  const resultMatch = responseText.match(/<result>(.*?)<\/result>/);
  const resultTextMatch = responseText.match(/<result-text>(.*?)<\/result-text>/);
  return {
    ok: Boolean(resultMatch && resultMatch[1] === "1"),
    text: resultTextMatch ? resultTextMatch[1] : deleted.text,
    transactionId: null,
  };
}
