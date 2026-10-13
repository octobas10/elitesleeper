import { promises as fs } from "node:fs";
import path from "node:path";

function storageRoot(): string {
  const configured = process.env.LEAD_STORAGE_DIR?.trim();
  if (configured) return configured;
  return path.join(process.cwd(), "data", "leads");
}

export async function ensureStorageDir(): Promise<string> {
  const dir = storageRoot();
  await fs.mkdir(dir, { recursive: true });
  return dir;
}

const ORDER_ID_RE = /^order-[a-zA-Z0-9T._-]+$/;

export async function readJsonRecord(id: string): Promise<Record<string, unknown> | null> {
  if (!ORDER_ID_RE.test(id)) return null;
  const dir = await ensureStorageDir();
  const filePath = path.join(dir, `${id}.json`);
  try {
    const raw = await fs.readFile(filePath, "utf8");
    return JSON.parse(raw) as Record<string, unknown>;
  } catch (error) {
    const code = (error as NodeJS.ErrnoException).code;
    if (code === "ENOENT") return null;
    throw error;
  }
}

export async function listOrderRecords(): Promise<Record<string, unknown>[]> {
  const dir = await ensureStorageDir();
  const names = await fs.readdir(dir);
  const records: Record<string, unknown>[] = [];
  for (const name of names) {
    if (!name.startsWith("order-") || !name.endsWith(".json")) continue;
    try {
      const raw = await fs.readFile(path.join(dir, name), "utf8");
      records.push(JSON.parse(raw) as Record<string, unknown>);
    } catch {
      /* skip bad files */
    }
  }
  return records;
}

export async function writeJsonRecord(
  prefix: string,
  record: Record<string, unknown>,
): Promise<{ id: string; path: string }> {
  const dir = await ensureStorageDir();
  const id =
    record.id && typeof record.id === "string"
      ? record.id
      : `${prefix}-${new Date().toISOString().replace(/[:.]/g, "-")}-${crypto.randomUUID().slice(0, 8)}`;
  const filePath = path.join(dir, `${id}.json`);
  const payload = { ...record, id };
  await fs.writeFile(filePath, `${JSON.stringify(payload, null, 2)}\n`, "utf8");
  return { id, path: filePath };
}

/** Returns true if this Stripe event id was already processed. */
export async function claimStripeEvent(eventId: string): Promise<boolean> {
  const dir = await ensureStorageDir();
  const eventsDir = path.join(dir, "stripe-events");
  await fs.mkdir(eventsDir, { recursive: true });
  const marker = path.join(eventsDir, `${eventId}.json`);
  try {
    await fs.writeFile(
      marker,
      `${JSON.stringify({ id: eventId, claimed_at: new Date().toISOString() }, null, 2)}\n`,
      { flag: "wx" },
    );
    return true;
  } catch (error) {
    const code = (error as NodeJS.ErrnoException).code;
    if (code === "EEXIST") return false;
    throw error;
  }
}
