"""Every composition the theme ships has exactly one h1, in its first section.

Starter pages use the page-no-title template, so nothing else on the page
supplies an h1: the first section's heading is the page's title. Checks the
demo homes, the page starters and every starter page, with nested pattern
references resolved. Exits 1 on any problem.
"""
import os, re, sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from rhythm_audit import compositions, THEME  # noqa: E402


def source(slug, depth=0):
    f = os.path.join(THEME, "patterns", slug + ".php")
    if not os.path.exists(f) or depth > 5:
        return ""
    s = open(f).read()
    return re.sub(r'<!-- wp:pattern \{"slug":"unapp/([a-z0-9-]+)"\} /-->',
                  lambda m: source(m.group(1), depth + 1), s)


def h1_count(slug):
    return len(re.findall(r"<h1[\s>]", source(slug)))


def main():
    bad = 0
    comps = compositions()
    for name, refs in comps.items():
        counts = [h1_count(r) for r in refs]
        problems = []
        if not refs or counts[0] != 1:
            problems.append(f"first section {refs[0] if refs else '-'} has {counts[0] if counts else 0} h1")
        for r, c in zip(refs[1:], counts[1:]):
            if c:
                problems.append(f"{r} adds {c} h1")
        if problems:
            bad += 1
            print(f"{name}: " + "; ".join(problems))
    print(f"compositions checked: {len(comps)}, with a heading problem: {bad}")
    sys.exit(1 if bad else 0)


if __name__ == "__main__":
    main()
