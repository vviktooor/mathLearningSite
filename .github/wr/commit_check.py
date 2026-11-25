#!/usr/bin/env python3
"""Conventional Commits check for commit messages and PR titles (WR standard).

Usage:
  python3 commit_check.py "feat(api): add login"          # check given subjects
  git log --format=%s origin/main..HEAD | python3 commit_check.py -   # check stdin, one per line

Exit code 0 = PASS, 1 = FAIL (prints every bad subject). Standard library only.
Author: WR
"""
from __future__ import annotations

import re
import sys

TYPES = ("feat", "fix", "docs", "style", "refactor", "perf", "test", "build", "ci", "chore", "revert")
PATTERN = re.compile(rf"^(?:{'|'.join(TYPES)})(?:\([a-z0-9][a-zA-Z0-9._/-]*\))?!?: [^A-Z\s].*[^.\s]$")
IGNORED = re.compile(r"^(Merge (pull request|branch|remote-tracking branch) |Revert \")")
MAX_LEN = 100


def problem(subject: str) -> str | None:
    """Reason the subject breaks the standard, or None when it is valid."""
    if IGNORED.match(subject):
        return None
    if len(subject) > MAX_LEN:
        return f"longer than {MAX_LEN} characters"
    if not PATTERN.match(subject):
        return "expected `type(scope): description` with type in " + ", ".join(TYPES)
    return None


def main(argv: list[str]) -> int:
    subjects = [line.strip() for line in sys.stdin] if argv == ["-"] else argv
    bad = [(s, problem(s)) for s in subjects if s and problem(s)]
    for subject, reason in bad:
        print(f"FAIL: {subject!r}: {reason}")
    print("PASS" if not bad else f"FAIL ({len(bad)} of {len([s for s in subjects if s])})")
    return 1 if bad else 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
