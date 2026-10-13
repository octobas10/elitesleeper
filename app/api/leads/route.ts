import { NextResponse } from "next/server";
import { notifyDesk } from "@/lib/payments/notify";
import { clientIp, isRateLimited } from "@/lib/payments/rate-limit";
import { writeJsonRecord } from "@/lib/payments/storage";

export const runtime = "nodejs";

function clean(value: FormDataEntryValue | null, max: number, keepBreaks = false): string {
  let text = typeof value === "string" ? value : "";
  text = text.replace(/\0/g, "");
  if (keepBreaks) {
    text = text.replace(/\r\n/g, "\n").replace(/\r/g, "\n");
  } else {
    text = text.replace(/\s+/g, " ");
  }
  text = text.trim().replace(/<[^>]*>/g, "");
  if (text.length > max) text = text.slice(0, max);
  return text;
}

function safeReturnPath(raw: string): string {
  const value = raw.trim();
  if (value === "/") return "/";
  if (/^\/(blog(?:\/[a-z0-9-]+)?|contact|order|opt-out)\/$/.test(value)) return value;
  return "";
}

function fail(request: Request, type: string, code: string, returnTo: string) {
  const fallback =
    type === "order" ? "/order/" : type === "optout" ? "/opt-out/" : "/contact/";
  const path = returnTo || fallback;
  const hash = path === "/" && type === "contact" ? "#contact" : "";
  return NextResponse.redirect(
    new URL(`${path}?error=${encodeURIComponent(code)}${hash}`, request.url),
    303,
  );
}

export async function POST(request: Request) {
  const origin = request.headers.get("origin");
  if (origin) {
    try {
      const host = request.headers.get("host")?.replace(/:\d+$/, "").toLowerCase() ?? "";
      const originHost = new URL(origin).hostname.toLowerCase();
      if (host && originHost !== host) {
        return fail(request, "contact", "save", "");
      }
    } catch {
      return fail(request, "contact", "save", "");
    }
  }

  const ip = clientIp(request.headers);
  if (await isRateLimited(ip, "leads")) {
    return fail(request, "contact", "rate", "");
  }

  let form: FormData;
  try {
    form = await request.formData();
  } catch {
    return fail(request, "contact", "missing", "");
  }

  if (clean(form.get("company_url"), 200) !== "") {
    return NextResponse.redirect(new URL("/thank-you/", request.url), 303);
  }

  const typeRaw = clean(form.get("form_type"), 20);
  const type =
    typeRaw === "order" || typeRaw === "contact" || typeRaw === "optout" ? typeRaw : "contact";
  const returnTo = safeReturnPath(clean(form.get("return_to"), 80));

  if (type === "order") {
    return NextResponse.redirect(new URL("/order/", request.url), 303);
  }

  const email = clean(form.get("email"), 120).toLowerCase();
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    return fail(request, type, "email", returnTo);
  }

  const lead: Record<string, unknown> = {
    form_type: type,
    email,
    submitted_at: new Date().toISOString(),
    ip,
  };

  if (type === "contact") {
    const first = clean(form.get("first_name"), 60);
    const last = clean(form.get("last_name"), 60);
    const subject = clean(form.get("subject"), 120);
    const message = clean(form.get("message"), 1000, true);
    const phone = clean(form.get("phone"), 24);
    if (!first || !last || !subject || !message) {
      return fail(request, type, "missing", returnTo);
    }
    Object.assign(lead, {
      first_name: first,
      last_name: last,
      phone,
      subject,
      message,
    });
  }

  if (type === "optout") {
    lead.request = "remove email from Elite Sleeper list";
  }

  const { id } = await writeJsonRecord(type, lead);
  await notifyDesk(`Elite Sleeper ${type} ${email}`, { ...lead, id });

  return NextResponse.redirect(
    new URL(`/thank-you/?sent=${encodeURIComponent(type)}`, request.url),
    303,
  );
}

export async function GET(request: Request) {
  return NextResponse.redirect(new URL("/contact/", request.url), 303);
}
