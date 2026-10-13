import { promises as fs } from "node:fs";
import path from "node:path";
import { ensureStorageDir } from "./storage";

type Bucket = Record<string, number[]>;

function windowSec(): number {
  const n = Number(process.env.RATE_LIMIT_WINDOW_SEC || 900);
  return Number.isFinite(n) && n > 0 ? n : 900;
}

function maxHits(): number {
  const n = Number(process.env.RATE_LIMIT_MAX || 8);
  return Number.isFinite(n) && n > 0 ? n : 8;
}

export async function isRateLimited(ip: string, bucket = "default"): Promise<boolean> {
  const dir = await ensureStorageDir();
  const file = path.join(dir, `rate_limit_${bucket}.json`);
  const now = Math.floor(Date.now() / 1000);
  const window = windowSec();
  const max = maxHits();

  let data: Bucket = {};
  try {
    const raw = await fs.readFile(file, "utf8");
    const parsed = JSON.parse(raw) as Bucket;
    if (parsed && typeof parsed === "object") data = parsed;
  } catch {
    data = {};
  }

  const prior = Array.isArray(data[ip]) ? data[ip].filter((t) => t > now - window) : [];
  if (prior.length >= max) {
    data[ip] = prior;
    await fs.writeFile(file, JSON.stringify(data), "utf8");
    return true;
  }

  data[ip] = [...prior, now];
  const keys = Object.keys(data);
  if (keys.length > 400) {
    const trimmed: Bucket = {};
    for (const key of keys.slice(-200)) trimmed[key] = data[key];
    data = trimmed;
  }
  await fs.writeFile(file, JSON.stringify(data), "utf8");
  return false;
}

export function clientIp(headers: Headers): string {
  const forwarded = headers.get("x-forwarded-for");
  if (forwarded) {
    const first = forwarded.split(",")[0]?.trim() ?? "";
    if (/^[0-9a-fA-F:.]+$/.test(first)) return first;
  }
  const real = headers.get("x-real-ip")?.trim() ?? "";
  if (/^[0-9a-fA-F:.]+$/.test(real)) return real;
  return "unknown";
}
