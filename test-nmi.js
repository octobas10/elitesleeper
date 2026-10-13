const fs = require("fs");
const path = require("path");

function loadLocalEnv() {
  const envPath = path.join(__dirname, ".env.local");
  const raw = fs.readFileSync(envPath, "utf8");
  for (const line of raw.split("\n")) {
    const trimmed = line.trim();
    if (!trimmed || trimmed.startsWith("#")) continue;
    const eq = trimmed.indexOf("=");
    if (eq === -1) continue;
    const key = trimmed.slice(0, eq).trim();
    const value = trimmed.slice(eq + 1).trim().replace(/^["']|["']$/g, "");
    if (!process.env[key]) process.env[key] = value;
  }
}

loadLocalEnv();

const apiKey = process.env.NMI_API_KEY;
const gatewayUrl = (process.env.NMI_GATEWAY_URL || "https://midkingzllc.transactiongateway.com/api/v2/three-step").replace(/\/$/, "");
if (!apiKey) {
  throw new Error("NMI_API_KEY is not set in .env.local");
}

const redirectUrl = process.env.SITE_URL
  ? `${process.env.SITE_URL.replace(/\/$/, "")}/api/webhooks/nmi`
  : "http://localhost:3000/api/webhooks/nmi";

const xml = `<?xml version="1.0" encoding="UTF-8"?>
<sale>
  <api-key>${apiKey}</api-key>
  <redirect-url>${redirectUrl}</redirect-url>
  <amount>29.95</amount>
</sale>`;

fetch(gatewayUrl, {
  method: "POST",
  headers: { "Content-Type": "text/xml" },
  body: xml,
})
  .then((res) => res.text())
  .then((body) => {
    const redacted = body.replaceAll(apiKey, "[redacted]");
    const formUrl = redacted.match(/<form-url>(.*?)<\/form-url>/);
    const resultText = redacted.match(/<result-text>(.*?)<\/result-text>/);
    console.log("gateway:", gatewayUrl);
    console.log("result-text:", resultText ? resultText[1] : "(none)");
    console.log("form-url:", formUrl ? formUrl[1] : "(none)");
    if (!formUrl) console.log(redacted);
  })
  .catch(console.error);
