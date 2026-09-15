#!/usr/bin/env python3
"""Parse Elite Biz Panel campus/program docs into institution-data.json."""

from __future__ import annotations

import json
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
SOURCE = Path(
    sys.argv[1]
    if len(sys.argv) > 1
    else "/home/buildco/.cursor/projects/home-buildco-highereducation-eilte/agent-tools/946e461f-1ecc-4888-89bb-2e0980970914.txt"
)
OUTPUT = ROOT / "plugins/hlm-lead-capture/includes/institution-data.json"

SECTION_MAP = {
    "Berkeley Download Zipcodes": "BERKELEY",
    "Universities Download Zipcodes": "UNIVERSITIES",
    "ECPI Download Zipcodes": "ECPI",
    "South West University Download Zipcodes": "SOUTH_UNI",
    "ACU Download Zipcodes": "ACU",
    "Canada Colleges Download Zipcodes": "CDI",
}

INSTITUTION_LABELS = {
    "BERKELEY": "Berkeley College",
    "UNIVERSITIES": "Universities",
    "ECPI": "ECPI University",
    "SOUTH_UNI": "South University",
    "ACU": "Abilene Christian University",
    "CDI": "CDI College",
}

CAMPUS_PREFIXES = (
    "Berkeley ",
    "Universities ",
    "ECPI -",
    "South University",
    "Abilene Christian",
)

PROGRAM_HINT = re.compile(
    r"(Bachelors?|Associates?|Diploma|Certificate|B\.S\.|B\.B\.A\.|A\.A\.S\.|"
    r"M\.B\.A\.|M\.S\.|M\.S\.N\.|D\.N\.P\.|Pharm\.D|Doctor|Nursing|Technology|"
    r"Management|Administration|Studies|Science in|Paralegal|Assisting|Engineering|"
    r"Year 2|Year 3|Informatics|Practitioner|Therapy|Cybersecurity|Mechatronics|"
    r"Development|Accounting|Psychology|Ministry|Leadership|Counselling|Counselor|"
    r"Massage|Pharmacy|Radiography|Surgical|Dental|Medical|Healthcare|Business|"
    r"Justice|Marketing|Communications|Fashion|Graphic|Interior|Welding|HVAC|"
    r"Electrician|Plumbing|Carpentry|Cosmetology|Barbering|Esthetic|Phlebotomy|"
    r"Technician|Specialist|Analyst|Programmer|Networking|Cyber|Cloud|Software|"
    r"Mobility|Homeland|Criminal|Occupational|Physical Therapist|Physician|"
    r"Anesthesia|Ministry|Informatics|Educator|Administrator)",
    re.IGNORECASE,
)


def is_campus_line(label: str, section: str) -> bool:
    if any(label.startswith(prefix) for prefix in CAMPUS_PREFIXES):
        return True
    if section == "UNIVERSITIES" and label.startswith("Universities "):
        return True
    if section == "CDI":
        # Canadian campus rows are short location names without degree keywords.
        return not PROGRAM_HINT.search(label) and len(label) < 80
    return False


def parse_source(text: str) -> dict:
    campus: dict[str, dict[str, str]] = {}
    programs: dict[str, dict[str, dict[str, str]]] = {}

    current_section: str | None = None
    current_campus: str | None = None

    for raw_line in text.splitlines():
        line = raw_line.strip()
        if not line or line.startswith("#") or line.startswith("|"):
            continue
        if line in SECTION_MAP:
            current_section = SECTION_MAP[line]
            campus.setdefault(current_section, {})
            programs.setdefault(current_section, {})
            current_campus = None
            continue
        if line.startswith("##") or "Direct Post" in line or "EliteBizPanel" in line:
            continue
        if "=>" not in line:
            continue

        code, label = [part.strip() for part in line.split("=>", 1)]
        if not code or not label or current_section is None:
            continue

        if is_campus_line(label, current_section):
            current_campus = code
            campus[current_section][code] = label
            programs[current_section].setdefault(code, {})
            continue

        if current_campus:
            programs[current_section][current_campus][code] = label

    return {
        "meta": {
            "source": "elitebizpanel.com/edu/api",
            "generated_by": "scripts/build-elite-catalog.py",
        },
        "institutions": INSTITUTION_LABELS,
        "campus": campus,
        "programs": programs,
    }


def main() -> None:
    text = SOURCE.read_text(encoding="utf-8")
    data = parse_source(text)
    OUTPUT.write_text(json.dumps(data, indent=2, ensure_ascii=False) + "\n", encoding="utf-8")

    counts = {
        inst: {
            "campuses": len(data["campus"].get(inst, {})),
            "programs": sum(len(p) for p in data["programs"].get(inst, {}).values()),
        }
        for inst in data["institutions"]
    }
    print(f"Wrote {OUTPUT}")
    for inst, stats in counts.items():
        print(f"  {inst}: {stats['campuses']} campuses, {stats['programs']} programs")


if __name__ == "__main__":
    main()
