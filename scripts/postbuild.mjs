import { cpSync } from "node:fs";
import { dirname, join } from "node:path";
import { fileURLToPath } from "node:url";

const root = join(dirname(fileURLToPath(import.meta.url)), "..");
cpSync(join(root, "php", "lead.php"), join(root, "out", "lead.php"));
console.log("Copied php/lead.php to out/lead.php");
