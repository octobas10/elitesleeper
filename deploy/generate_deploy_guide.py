#!/usr/bin/env python3
"""Regenerate: python3 deploy/generate_deploy_guide.py"""
from docx import Document
from docx.shared import Pt, Inches
from docx.enum.text import WD_ALIGN_PARAGRAPH
from pathlib import Path

OUT = Path(__file__).resolve().parent / "HLM-GitHub-Actions-Deploy-Guide.docx"

doc = Document()

title = doc.add_heading("HLM Deploy Guide", 0)
title.alignment = WD_ALIGN_PARAGRAPH.CENTER
sub = doc.add_paragraph("Git push from Mac → GitHub Actions → Production WordPress")
sub.alignment = WD_ALIGN_PARAGRAPH.CENTER
sub.runs[0].italic = True

doc.add_paragraph("Repository: octobas10/hlm-ebp")
doc.add_paragraph("Site: https://higherlearningmarketers.com/")
doc.add_paragraph("Last updated: July 2026")
doc.add_paragraph()


def h(text, level=1):
    doc.add_heading(text, level=level)


def p(text, bold=False):
    para = doc.add_paragraph()
    run = para.add_run(text)
    if bold:
        run.bold = True
    return para


def bullet(text):
    doc.add_paragraph(text, style="List Bullet")


def code(text):
    para = doc.add_paragraph()
    run = para.add_run(text)
    run.font.name = "Courier New"
    run.font.size = Pt(9)
    para.paragraph_format.left_indent = Inches(0.25)


def table(headers, rows):
    t = doc.add_table(rows=1 + len(rows), cols=len(headers))
    t.style = "Table Grid"
    hdr = t.rows[0].cells
    for i, htext in enumerate(headers):
        hdr[i].text = htext
        for run in hdr[i].paragraphs[0].runs:
            run.bold = True
    for ri, row in enumerate(rows):
        cells = t.rows[ri + 1].cells
        for ci, val in enumerate(row):
            cells[ci].text = val
    doc.add_paragraph()


# --- PART A: SSH KEYS ---
h("PART A — Create SSH keys on Mac (do this first)", 1)
p(
    "SSH uses two files: a private key (secret, stays on your Mac) and a "
    "public key (.pub, shared with GitHub and your server). "
    "Complete this section before the deploy setup."
)

h("A1. Check if you already have a key", 2)
p("On Mac, open Terminal and run:")
code("ls -la ~/.ssh/")
p("If you see id_ed25519_personal and id_ed25519_personal.pub, skip to A4.")
p("Common existing keys: id_ed25519, id_rsa (and matching .pub files).")

h("A2. Generate a new SSH key pair", 2)
p("Run this on your Mac (replace email with your GitHub email):")
code(
    "ssh-keygen -t ed25519 -C \"octobas@gmail.com\" \\\n"
    "  -f ~/.ssh/id_ed25519_personal -N \"\""
)
bullet("-t ed25519  = modern secure key type (recommended)")
bullet("-f path     = file name/location for the key")
bullet("-N \"\"      = no passphrase (press Enter if prompted instead)")
bullet("-C email    = label so you recognise the key later")
p("When asked to overwrite, type n unless you want to replace an old key.")

h("A3. Understand the two files created", 2)
table(
    ["File", "Type", "Keep secret?", "Used for"],
    [
        [
            "~/.ssh/id_ed25519_personal",
            "Private key",
            "YES — never share",
            "GitHub secret SSH_PRIVATE_KEY; proves identity",
        ],
        [
            "~/.ssh/id_ed25519_personal.pub",
            "Public key",
            "No — safe to share",
            "GitHub account SSH keys; server authorized_keys",
        ],
    ],
)
p("Rule: .pub = public (share). File without .pub = private (never upload to GitHub or email).")

h("A4. Set correct file permissions", 2)
code(
    "chmod 700 ~/.ssh\n"
    "chmod 600 ~/.ssh/id_ed25519_personal\n"
    "chmod 644 ~/.ssh/id_ed25519_personal.pub"
)
p("SSH will refuse to use keys if permissions are too open.")

h("A5. View and copy your keys", 2)
p("View public key (one line starting with ssh-ed25519):")
code("cat ~/.ssh/id_ed25519_personal.pub")
p("Copy public key to clipboard:")
code("cat ~/.ssh/id_ed25519_personal.pub | pbcopy")
p("View private key (multiple lines, BEGIN/END):")
code("cat ~/.ssh/id_ed25519_personal")
p("Copy private key to clipboard (for GitHub secret only):")
code("cat ~/.ssh/id_ed25519_personal | pbcopy")

h("A6. Optional — tell Mac to use this key for GitHub", 2)
p("Create or edit ~/.ssh/config on your Mac:")
code(
    "Host github.com\n"
    "  HostName github.com\n"
    "  User git\n"
    "  IdentityFile ~/.ssh/id_ed25519_personal\n"
    "  IdentitiesOnly yes"
)
code("chmod 600 ~/.ssh/config")
p("Test GitHub connection:")
code("ssh -T git@github.com")

h("A7. SSH key checklist (Part A)", 2)
for c in [
    "Key pair exists at ~/.ssh/id_ed25519_personal (+ .pub)",
    "Private key permissions are 600",
    "Public key copied and ready for GitHub + server",
    "ssh -T git@github.com works (after adding .pub to GitHub in Step 1 below)",
]:
    bullet("☐  " + c)

doc.add_page_break()

# --- PART B: DEPLOY ---
h("PART B — Deploy setup (Steps 1–9)", 1)

h("1. Overview", 2)
p("Automatic deployment when you push code from your Mac to GitHub.")
p("What gets deployed (HLM code only):", bold=True)
bullet("themes/hlm-theme-kadence/  →  wp-content/themes/hlm-theme-kadence/")
bullet("plugins/hlm-lead-capture/  →  wp-content/plugins/hlm-lead-capture/")
bullet("plugins/hlm-security/      →  wp-content/plugins/hlm-security/")
bullet("plugins/hlm-seo/           →  wp-content/plugins/hlm-seo/")
p("What is NOT deployed:", bold=True)
bullet("WordPress core, wp-config, database, uploads")
bullet("Other plugins — manage via WordPress Admin")
p("Flow:", bold=True)
code("Mac: edit  →  git push  →  GitHub Actions (Mac runner)  →  SSH/rsync  →  live site")

h("2. Your server details", 2)
table(
    ["Item", "Value"],
    [
        ["GitHub account", "octobas10"],
        ["Repository", "https://github.com/octobas10/hlm-ebp"],
        ["Server IP (SSH_HOST)", "74.205.70.188"],
        ["SSH user (SSH_USER)", "vish"],
        ["WordPress root (WP_ROOT)", "/usr/virtual/www.higherlearningmarketers.com"],
        ["Mac SSH private key", "~/.ssh/id_ed25519_personal"],
        ["Mac SSH public key", "~/.ssh/id_ed25519_personal.pub"],
    ],
)

h("3. SSH keys — three places (do not mix up)", 2)
table(
    ["Purpose", "Key type", "Where it goes"],
    [
        ["Push code to GitHub", "Public (.pub)", "GitHub account → Settings → SSH keys"],
        ["Deploy to server", "Public (.pub)", "Server → vish ~/.ssh/authorized_keys"],
        ["GitHub Actions deploy", "Private (no .pub)", "Repo → Settings → Secrets → SSH_PRIVATE_KEY"],
    ],
)
p("You do NOT need Deploy keys in repo settings.")

h("4. Step-by-step setup", 2)

h("STEP 1 — Add public key to GitHub account", 3)
p("Complete Part A first. Then on Mac:")
code("cat ~/.ssh/id_ed25519_personal.pub | pbcopy")
bullet("Log in as octobas10")
bullet("Open https://github.com/settings/keys")
bullet("New SSH key → paste → Save")
p("Test:")
code("ssh -T git@github.com")

h("STEP 2 — Point local repo at octobas10", 3)
code(
    "cd ~/highereducation_eilte\n"
    "git remote set-url origin git@github.com:octobas10/hlm-ebp.git\n"
    "git fetch origin"
)

h("STEP 3 — Add public key on server", 3)
code("cat ~/.ssh/id_ed25519_personal.pub")
p("SSH into server:")
code("ssh vish@74.205.70.188")
p("On server:")
code(
    "mkdir -p ~/.ssh\n"
    "chmod 700 ~/.ssh\n"
    "nano ~/.ssh/authorized_keys"
)
p("Paste the public key line, save, then:")
code("chmod 600 ~/.ssh/authorized_keys\nexit")
p("Test from Mac (no password):")
code("ssh -i ~/.ssh/id_ed25519_personal vish@74.205.70.188")

h("STEP 4 — Add GitHub repository secrets", 3)
p("Open: https://github.com/octobas10/hlm-ebp/settings/secrets/actions")
p("SECRETS tab only (not Variables):")
table(
    ["Secret name", "Value"],
    [
        ["SSH_HOST", "74.205.70.188"],
        ["SSH_USER", "vish"],
        ["WP_ROOT", "/usr/virtual/www.higherlearningmarketers.com"],
        ["SSH_PRIVATE_KEY", "Full ~/.ssh/id_ed25519_personal file (BEGIN/END lines)"],
    ],
)
code("cat ~/.ssh/id_ed25519_personal | pbcopy")

h("STEP 5 — Install self-hosted runner on Mac (one time)", 3)
p("GitHub cloud cannot SSH to your server. Runner on Mac runs deploy in background.")
bullet("https://github.com/octobas10/hlm-ebp/settings/actions/runners")
bullet("New self-hosted runner → macOS → ARM64 or x64")
code("mkdir ~/actions-runner && cd ~/actions-runner")
p("Run curl + tar from GitHub, then:")
code("./config.sh --url https://github.com/octobas10/hlm-ebp --token YOUR_TOKEN")
p("Press Enter for defaults. Install service:")
code("sudo ./svc.sh install\nsudo ./svc.sh start")

h("STEP 6 — Push workflow to GitHub", 3)
p(".github/workflows/deploy-ssh.yml must contain: runs-on: self-hosted")
code(
    "cd ~/highereducation_eilte\n"
    "git add .github/workflows/deploy-ssh.yml\n"
    "git commit -m \"Add SSH deploy workflow\"\n"
    "git push origin main"
)

h("STEP 7 — Run first deploy", 3)
bullet("https://github.com/octobas10/hlm-ebp/actions")
bullet("Deploy via SSH → Run workflow")

h("STEP 8 — Verify on production", 3)
bullet("https://higherlearningmarketers.com/ (Cmd+Shift+R hard refresh)")

h("STEP 9 — Daily workflow", 3)
code(
    "cd ~/highereducation_eilte\n"
    "git add .\n"
    "git commit -m \"Describe change\"\n"
    "git push origin main"
)

h("5. Do NOT do on the server", 2)
bullet("No git init inside WordPress folder")
bullet("No repo clone inside wp-content")
bullet("Server WordPress stays — only theme/plugin files are copied")

h("6. Troubleshooting", 2)
table(
    ["Error", "Fix"],
    [
        ["workflow scope on push", "Regenerate GitHub PAT with workflow scope"],
        ["WP_ROOT secret is not set", "Add under Secrets tab, not Variables"],
        ["Connection timed out", "Use self-hosted Mac runner"],
        ["Permission denied (publickey)", "Check private key secret + server authorized_keys"],
        ["Waiting for a runner", "sudo ./svc.sh start in ~/actions-runner"],
        ["No such file id_ed25519_personal", "Complete Part A — generate keys first"],
    ],
)

h("7. Full checklist", 2)
checks = [
    "PART A: SSH key pair created on Mac",
    "PART A: Private key 600, public key copied",
    "STEP 1: Public key on octobas10 GitHub account",
    "STEP 2: git remote → git@github.com:octobas10/hlm-ebp.git",
    "STEP 3: Public key on server authorized_keys",
    "STEP 3: SSH to server works without password",
    "STEP 4: All 4 GitHub secrets set",
    "STEP 5: Mac runner Idle on GitHub",
    "STEP 6: deploy-ssh.yml pushed (runs-on: self-hosted)",
    "STEP 7: First deploy green",
    "STEP 8: Site shows changes",
]
for c in checks:
    bullet("☐  " + c)

doc.add_paragraph()
p("End of guide.", bold=True)

doc.save(OUT)
print(f"Created: {OUT}")
