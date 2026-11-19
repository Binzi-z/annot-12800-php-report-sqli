#!/usr/bin/env python3
"""baseline：朴素做法——凡出现 ->query( / shell_exec( / file_get_contents( / curl_init(
就报一行。召回高、误报也高（decoy 全部中招），正是题目要求改进的起点。
"""
import json, os, re, sys

SINK_RE = re.compile(r"(->query\s*\(|shell_exec\s*\(|file_get_contents\s*\(|curl_init\s*\()")
ROOTS = ("public", "src")

def main():
    findings = []
    for root in ROOTS:
        for dirpath, _, names in os.walk(root):
            for n in sorted(names):
                if not n.endswith(".php"):
                    continue
                p = os.path.join(dirpath, n).replace("\\", "/")
                with open(p, encoding="utf-8") as fh:
                    for i, line in enumerate(fh, 1):
                        if SINK_RE.search(line):
                            findings.append({"file": p, "line": i})
    findings.sort(key=lambda f: (f["file"], f["line"]))
    json.dump({"findings": findings}, sys.stdout, indent=1)
    sys.stdout.write("\n")

if __name__ == "__main__":
    main()
