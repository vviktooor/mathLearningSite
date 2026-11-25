#!/usr/bin/env python3
"""WR repository gate for CI: README EN/PL, Markdown docs, secrets.

Usage: python3 repo_check.py [repo_root]
Exit code 0 = no FAIL (warnings are NOT VERIFIED), 1 = at least one FAIL.
In GitHub Actions every finding is also printed as an annotation.
Standalone (standard library only) so it can be copied to `.github/wr/`.
Author: WR
"""
from __future__ import annotations

import os
import re
import subprocess
import sys
from pathlib import Path

FAIL, WARN = "FAIL", "NOT VERIFIED"
SKIP_DIRS = {".git", "node_modules", ".venv", "venv", "vendor", "dist", "build", "__pycache__"}
SECRET = re.compile(
    r"sk-(?:proj-)?[A-Za-z0-9_-]{20,}"  # OpenAI
    r"|AKIA[0-9A-Z]{16}"  # AWS access key
    r"|gh[pousr]_[A-Za-z0-9]{36}"  # GitHub token
    r"|xox[abpr]-[A-Za-z0-9-]{10,}"  # Slack token
    r"|-----BEGIN (?:RSA |EC |OPENSSH |DSA )?PRIVATE KEY-----"
)
SECRET_FILES = {".env", "id_rsa", "id_ed25519", "known_hosts"}
TEXT_SUFFIXES = {".py", ".js", ".mjs", ".ts", ".tsx", ".php", ".cs", ".ps1", ".sh", ".json", ".yml", ".yaml",
                 ".toml", ".ini", ".cfg", ".env", ".txt", ".md", ".html", ".sql", ".xml", ".rsc", ".conf"}
OLD_README = (
    (re.compile(r"\*\*English\*\*\s*·\s*\[Polski\]|\*\*Polski\*\*\s*·\s*\[English\]"),
     "old bold language switch; use the two badges from the WR README standard"),
    (re.compile(r"^.*·\s*\[Report an issue\]", re.M), "'Report an issue' link at the top; move it to the end of License"),
    (re.compile(r"WR\s*·\s*vviktooor\s*$", re.M), "signature footer; the WR README standard has none"),
)
DOCS = ("deployment", "user-guide", "technical-guide")


def headings(path: Path) -> int:
    return sum(1 for line in path.read_text(encoding="utf-8", errors="replace").splitlines() if line.startswith("## "))


def files(root: Path):
    """Tracked files when root is a git checkout (ignored .env stays local), else every file."""
    try:
        out = subprocess.run(["git", "-C", str(root), "ls-files", "-z"], capture_output=True, check=True).stdout
        for rel in out.decode("utf-8", "replace").split("\0"):
            if rel and not SKIP_DIRS.intersection(Path(rel).parts) and (root / rel).is_file():
                yield root / rel
        return
    except (OSError, subprocess.CalledProcessError):
        pass
    for dirpath, dirnames, filenames in os.walk(root):
        dirnames[:] = [d for d in dirnames if d not in SKIP_DIRS]
        for name in filenames:
            yield Path(dirpath, name)


def check_readme(root: Path) -> list[tuple[str, str]]:
    en, pl = root / "README.md", root / "README.pl.md"
    if not en.is_file():
        return [(FAIL, "README.md is missing")]
    found = []
    if not pl.is_file():
        found.append((WARN, "README.pl.md (Polish version) is missing"))
    elif headings(en) != headings(pl):
        found.append((FAIL, f"README.md has {headings(en)} sections, README.pl.md has {headings(pl)}"))
    for path in (en, pl):
        if path.is_file():
            text = path.read_text(encoding="utf-8", errors="replace")
            found += [(FAIL, f"{path.name}: {why}") for pattern, why in OLD_README if pattern.search(text)]
    if not (root / ".github" / "readme" / "banner.svg").is_file():
        found.append((WARN, "README banner .github/readme/banner.svg is missing"))
    return found


def check_docs(root: Path) -> list[tuple[str, str]]:
    found = []
    for name in DOCS:
        en, pl = root / "docs" / f"{name}.md", root / "docs" / "pl" / f"{name}.md"
        if en.is_file() and pl.is_file() and headings(en) != headings(pl):
            found.append((FAIL, f"docs/{name}.md and docs/pl/{name}.md have different section counts"))
        elif en.is_file() and not pl.is_file():
            found.append((WARN, f"docs/pl/{name}.md (Polish version) is missing"))
    docs = root / "docs"
    if docs.is_dir():
        for path in sorted(docs.rglob("*.html")):
            found.append((WARN, f"{path.relative_to(root)}: documentation should be Markdown (HTML only for app pages)"))
    return found


def check_secrets(root: Path) -> list[tuple[str, str]]:
    found = []
    for path in files(root):
        rel = path.relative_to(root)
        if path.name in SECRET_FILES:
            found.append((FAIL, f"{rel}: file must not be committed"))
            continue
        if path.suffix.lower() not in TEXT_SUFFIXES or path.stat().st_size > 1_000_000:
            continue
        text = path.read_text(encoding="utf-8", errors="ignore")
        for number, line in enumerate(text.splitlines(), 1):
            if SECRET.search(line):
                found.append((FAIL, f"{rel}:{number}: looks like a secret (key, token or private key)"))
    return found


def check(root: Path) -> list[tuple[str, str]]:
    return check_readme(root) + check_docs(root) + check_secrets(root)


def main(argv: list[str]) -> int:
    root = Path(argv[0] if argv else ".").resolve()
    found = check(root)
    actions = os.environ.get("GITHUB_ACTIONS") == "true"
    for status, message in found:
        print(f"{status}: {message}")
        if actions:
            print(f"::{'error' if status == FAIL else 'warning'} title=WR {status}::{message}")
    fails = sum(1 for s, _ in found if s == FAIL)
    warns = len(found) - fails
    print("PASS" if not found else f"{'FAIL' if fails else 'NOT VERIFIED'} ({fails} fail, {warns} warning)")
    return 1 if fails else 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
