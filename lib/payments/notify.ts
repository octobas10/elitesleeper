export async function notifyDesk(
  subject: string,
  record: Record<string, unknown>,
): Promise<{ mailed: boolean; hooked: boolean }> {
  const webhook = process.env.LEAD_WEBHOOK_URL?.trim() || "";
  let hooked = false;
  if (/^https:\/\//i.test(webhook)) {
    try {
      const res = await fetch(webhook, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(record),
        signal: AbortSignal.timeout(8000),
      });
      hooked = res.ok;
    } catch {
      hooked = false;
    }
  }

  const to = process.env.LEAD_NOTIFY_EMAIL?.trim() || "";
  let mailed = false;
  // Server mail without Resend: best-effort HTTP notify only when webhook set.
  // If RESEND_API_KEY is present, send via Resend.
  const resendKey = process.env.RESEND_API_KEY?.trim() || "";
  if (to && resendKey) {
    const from = process.env.LEAD_NOTIFY_FROM?.trim() || "Elite Sleeper <support@elitesleeper.com>";
    const lines = Object.entries(record)
      .filter(([key]) => key !== "ip")
      .map(([key, value]) => `${key}: ${String(value ?? "")}`);
    try {
      const res = await fetch("https://api.resend.com/emails", {
        method: "POST",
        headers: {
          Authorization: `Bearer ${resendKey}`,
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          from,
          to: [to],
          subject: subject.replace(/[\r\n]/g, ""),
          text: lines.join("\n"),
        }),
        signal: AbortSignal.timeout(8000),
      });
      mailed = res.ok;
    } catch {
      mailed = false;
    }
  }

  return { mailed, hooked };
}
