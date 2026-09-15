#!/usr/bin/env python3
"""Convert Jinja templates to PHP partials."""

from __future__ import annotations

import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[2]
SRC = ROOT / "app" / "templates"
DST = ROOT / "site" / "templates"


def convert_url_for(expr: str) -> str:
    expr = re.sub(
        r"url_for\(\s*'static'\s*,\s*filename='([^']+)'\s*\)",
        r"asset('\1')",
        expr,
    )
    expr = re.sub(
        r'url_for\(\s*"static"\s*,\s*filename="([^"]+)"\s*\)',
        r'asset("\1")',
        expr,
    )
    return expr


def php_default(s: str) -> str:
    s = s.strip()
    if s in ("[]", "{}"):
        return s
    if s.startswith("'") or s.startswith('"'):
        return s
    if s.isdigit():
        return s
    if s in ("true", "false"):
        return s
    return php_expr(s)


def php_expr(expr: str, default: str | None = None) -> str:
    expr = expr.strip()
    if default is not None:
        return f"({php_expr(expr)} ?? {php_expr(default)})"

    if len(expr) >= 2 and ((expr[0] == "'" and expr[-1] == "'") or (expr[0] == '"' and expr[-1] == '"')):
        return expr

    expr = convert_url_for(expr)

    ternary = re.match(r"^(.+?)\s+if\s+(.+?)\s+else\s+(.+)$", expr, re.DOTALL)
    if ternary:
        true_v = ternary.group(1).strip()
        cond = ternary.group(2).strip()
        false_v = ternary.group(3).strip()
        return f"({php_if(cond)} ? {php_expr(true_v)} : {php_expr(false_v)})"

    if re.search(r"\s+or\s+", expr):
        parts = re.split(r"\s+or\s+", expr)
        result = php_expr(parts[-1].strip())
        for part in reversed(parts[:-1]):
            result = f"({php_expr(part.strip())} ?? {result})"
        return result

    expr = re.sub(
        r"(\$[\w\[\]'\"]+|\w+)\|length",
        lambda m: f"count({php_expr(m.group(1))})",
        expr,
    )

    default_match = re.match(r"^(.+?)\|default\((.+)\)$", expr)
    if default_match:
        return f"({php_expr(default_match.group(1))} ?? {php_expr(default_match.group(2))})"

    if "~" in expr:
        parts = [p.strip() for p in expr.split("~")]
        return " . ".join(php_expr(p) for p in parts)

    expr = re.sub(r"\bsite\.(\w+)\b", r"$site['\1']", expr)
    expr = re.sub(r"\bmedia\.(\w+)\b", r"$media['\1']", expr)
    expr = re.sub(r"\bcontact\.(\w+)\b", r"$contact['\1']", expr)

    if "." in expr and not expr.startswith("$") and not expr.startswith("'") and not expr.startswith('"'):
        obj, attr = expr.split(".", 1)
        attr = attr.split("|")[0]
        return f"${obj}['{attr}']"

    slice_match = re.match(r"^(\w+)\[:(\d+)\]$", expr)
    if slice_match:
        return f"array_slice(${slice_match.group(1)}, 0, {slice_match.group(2)})"

    expr = re.sub(r"\b(\w+)\s+is\s+defined\b", r"isset($\1)", expr)
    expr = re.sub(r"\b(\w+)\s+is\s+not\s+defined\b", r"!isset($\1)", expr)

    expr = expr.replace("loop.index0", "$loop_index0")

    if re.match(r"^[a-zA-Z_]\w*$", expr):
        return f"${expr}"

    return expr


def php_foreach_source(expr: str) -> str:
    expr = expr.strip()
    if expr.endswith(".items()"):
        return php_expr(expr[: -len(".items()")])
    return php_expr(expr)


def php_if(expr: str) -> str:
    expr = expr.strip()
    expr = convert_url_for(expr)

    in_match = re.match(r"^(.+?)\s+in\s+(.+)$", expr)
    if in_match:
        needle = php_expr(in_match.group(1).strip())
        haystack = php_expr(in_match.group(2).strip())
        return f"str_contains((string)({haystack}), (string)({needle}))"

    if re.search(r"\s+or\s+", expr):
        parts = re.split(r"\s+or\s+", expr)
        return " || ".join(f"({php_if(part.strip())})" for part in parts)

    if re.search(r"\s+and\s+", expr):
        parts = re.split(r"\s+and\s+", expr)
        return " && ".join(f"({php_if(part.strip())})" for part in parts)

    and_match = re.match(r"^(\w+(?:\.\w+)+)\s+and\s+(\w+(?:\.\w+)+)$", expr)
    if and_match:
        left = php_expr(and_match.group(1))
        right = php_expr(and_match.group(2))
        return f"(!empty({left}) && !empty({right}))"

    not_match = re.match(r"^not\s+(.+)$", expr)
    if not_match:
        inner = php_if(not_match.group(1).strip())
        return f"!({inner})"

    expr = re.sub(r"\s==\s", " === ", expr)
    expr = re.sub(r"\s!=\s", " !== ", expr)

    result = php_expr(expr)
    result = result.replace("$loop_index0 % 2 === 1", "($loop_index0 % 2) === 1")
    return result


def extract_block(text: str, block_name: str) -> tuple[str, str]:
    pattern = rf"\{{%\s*block\s+{block_name}\s*%\}}(.*?)\{{%\s*endblock\s*%\}}"
    match = re.search(pattern, text, re.DOTALL)
    if not match:
        return text, ""
    block_content = match.group(1).strip()
    text = text[: match.start()] + text[match.end() :]
    return text, block_content


def preprocess_layout(text: str) -> str:
    text = text.replace("{% block content %}{% endblock %}", "<?= $content ?? '' ?>")
    text = re.sub(
        r"\{%\s*block\s+head\s*%\}\{%\s*endblock\s*%\}",
        "<?= $head ?? '' ?>",
        text,
    )
    text = re.sub(
        r"\{%\s*block\s+scripts\s*%\}\{%\s*endblock\s*%\}",
        "<?= $scripts ?? '' ?>",
        text,
    )
    return convert_jinja(text, is_layout=True)


def preprocess_page(text: str) -> str:
    text = re.sub(r"\{%\s*extends\s+['\"][^'\"]+['\"]\s*%\}\s*", "", text)
    text, scripts = extract_block(text, "scripts")
    text, head = extract_block(text, "head")
    text = re.sub(r"\{%\s*block\s+content\s*%\}", "", text)
    text = re.sub(r"\{%\s*endblock\s*%\}", "", text)

    converted = convert_jinja(text)

    if head:
        converted += (
            "\n<?php ob_start(); ?>\n"
            + convert_jinja(head)
            + "\n<?php $page_head = ob_get_clean(); ?>\n"
        )

    if scripts:
        converted += (
            "\n<?php ob_start(); ?>\n"
            + convert_jinja(scripts)
            + "\n<?php $page_scripts = ob_get_clean(); ?>\n"
        )

    return converted


def convert_jinja(text: str, is_layout: bool = False) -> str:
    def repl_set(match: re.Match) -> str:
        var = match.group(1)
        expr = match.group(2).strip()
        if "|default(" in expr:
            base, rest = expr.split("|default(", 1)
            default = rest.rsplit(")", 1)[0]
            return f"<?php ${var} = {php_expr(base.strip(), default)}; ?>"
        return f"<?php ${var} = {php_expr(expr)}; ?>"

    text = re.sub(
        r"\{%\s*set\s+(\w+)\s*=\s*([^%]+?)\s*%\}",
        repl_set,
        text,
    )

    text = re.sub(
        r"\{%\s*include\s+['\"]([^'\"]+)['\"]\s*%\}",
        lambda m: f"<?php partial('{m.group(1).replace('.html', '')}', get_defined_vars()); ?>",
        text,
    )

    text = re.sub(
        r"\{%\s*for\s+(\w+)\s+in\s+([^%]+?)\s*%\}",
        lambda m: f"<?php foreach ({php_expr(m.group(2).strip())} as ${m.group(1)}): ?>",
        text,
    )
    text = re.sub(
        r"\{%\s*for\s+(\w+),\s*(\w+)\s+in\s+([^%]+?)\s*%\}",
        lambda m: f"<?php foreach ({php_foreach_source(m.group(3).strip())} as ${m.group(1)} => ${m.group(2)}): ?>",
        text,
    )
    text = re.sub(r"\{%\s*endfor\s*%\}", "<?php endforeach; ?>", text)

    text = re.sub(
        r"\{%\s*if\s+([^%]+?)\s*%\}",
        lambda m: f"<?php if ({php_if(m.group(1).strip())}): ?>",
        text,
    )
    text = re.sub(
        r"\{%\s*elif\s+([^%]+?)\s*%\}",
        lambda m: f"<?php elseif ({php_if(m.group(1).strip())}): ?>",
        text,
    )
    text = re.sub(r"\{%\s*else\s*%\}", "<?php else: ?>", text)
    text = re.sub(r"\{%\s*endif\s*%\}", "<?php endif; ?>", text)

    text = re.sub(
        r"\{\{\s*url_for\('static',\s*filename='([^']+)'\)\s*\}\}",
        r"<?= e(asset('\1')) ?>",
        text,
    )
    text = re.sub(
        r"\{\{\s*url_for\('static',\s*filename=\"([^\"]+)\"\)\s*\}\}",
        r'<?= e(asset("\1")) ?>',
        text,
    )

    def repl_var(match: re.Match) -> str:
        expr = match.group(1).strip()
        if "|safe" in expr:
            inner = expr.replace("|safe", "").strip()
            return f"<?= {php_expr(inner)} ?>"
        if "|tojson" in expr:
            inner = expr.replace("|tojson", "").strip()
            return f"<?= json_attr({php_expr(inner)}) ?>"
        if "|urlencode" in expr:
            inner = expr.replace("|urlencode", "").strip()
            return f"<?= e(urlencode((string)({php_expr(inner)}))) ?>"
        if "|trim" in expr:
            inner = expr.replace("|trim", "").strip()
            return f"<?= e(trim((string)({php_expr(inner)}))) ?>"
        if "|default(" in expr:
            base, rest = expr.split("|default(", 1)
            default = rest.rsplit(")", 1)[0]
            return f"<?= e((string)({php_expr(base.strip(), default)})) ?>"
        return f"<?= e((string)({php_expr(expr)})) ?>"

    text = re.sub(r"\{\{\s*([^}]+?)\s*\}\}", repl_var, text)
    return text


def main() -> None:
    for src in SRC.rglob("*.html"):
        rel = src.relative_to(SRC)
        dst = DST / rel.with_suffix(".php")
        dst.parent.mkdir(parents=True, exist_ok=True)
        text = src.read_text(encoding="utf-8")

        if rel.name == "base.html":
            converted = preprocess_layout(text)
        elif "pages/" in str(rel) or str(rel).startswith("pages/"):
            converted = preprocess_page(text)
        else:
            converted = convert_jinja(text)

        if "loop.index0" in converted or "$loop_index0" in converted:
            converted = converted.replace(
                "<?php foreach ($timeline as $m): ?>",
                "<?php foreach ($timeline as $loop_index0 => $m): ?>",
            )

        converted = converted.replace(
            "request.args.get('type', '') if request is defined else ''",
            "($_GET['type'] ?? '')",
        )
        converted = converted.replace("$error_message", "$error")

        dst.write_text(converted, encoding="utf-8")
        print(f"Converted {rel} -> {dst.relative_to(ROOT)}")


if __name__ == "__main__":
    main()
