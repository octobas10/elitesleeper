"use client";

import { useEffect, useState } from "react";

declare global {
  interface Window {
    CollectCheckout?: {
      redirectToCheckout: (options: Record<string, unknown>) => Promise<unknown>;
    };
  }
}

const SCRIPT_SRC = "https://midkingzllc.transactiongateway.com/token/CollectCheckout.js";

export function CollectCheckoutRedirect({
  checkoutKey,
  sku,
  quantity,
  orderId,
  monthly,
}: {
  checkoutKey: string;
  sku: string;
  quantity: number;
  orderId: string;
  monthly: boolean;
}) {
  const [error, setError] = useState("");

  useEffect(() => {
    if (!checkoutKey || !sku) {
      setError("Secure checkout is not configured. Email support@elitesleeper.com.");
      return;
    }

    const origin = window.location.origin.replace(/\/$/, "");
    const script = document.createElement("script");
    script.src = SCRIPT_SRC;
    script.setAttribute("data-checkout-key", checkoutKey);
    script.async = true;

    script.onload = () => {
      if (!window.CollectCheckout) {
        setError("Checkout failed to load. Refresh and try again.");
        return;
      }

      const options: Record<string, unknown> = {
        lineItems: [{ sku, quantity }],
        type: "sale",
        collectShippingInfo: false,
        successUrl: `${origin}/thank-you/?sent=paid&session_id=(TRANSACTION_ID)&order=${encodeURIComponent(orderId)}`,
        cancelUrl: `${origin}/order/?error=cancelled`,
        receipt: {
          showReceipt: false,
          redirectToSuccessUrl: true,
        },
      };

      if (monthly) {
        options.customerVault = { addCustomer: true };
      }

      void window.CollectCheckout.redirectToCheckout(options).then((result) => {
        if (result) {
          setError(typeof result === "string" ? result : "Checkout could not start. Check the product SKU in the merchant portal.");
        }
      });
    };

    script.onerror = () => {
      setError("Could not reach the payment page. Refresh and try again.");
    };

    document.body.appendChild(script);
    return () => {
      script.remove();
    };
  }, [checkoutKey, sku, quantity, orderId, monthly]);

  return (
    <div className="form">
      {error ? (
        <p className="note note-bad" role="alert">
          {error}
        </p>
      ) : (
        <p>Opening secure checkout…</p>
      )}
    </div>
  );
}
