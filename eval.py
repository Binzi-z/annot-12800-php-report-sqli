#!/usr/bin/env python3
"""eval.py —— 拿分析器输出和 labels.json 对比

用法：python eval.py findings.json
findings 支持两种：SARIF，或 {"findings":[{"file":..,"line":..}]}

注意：Windows PowerShell 5.1 的 `>` 重定向会把 stdout 写成 UTF-16LE，
所以这里按 UTF-8 / UTF-16 / GB18030 依次尝试解码，避免换台机器就跑不起来。
"""
import json, sys

def load_findings(path):
    raw = open(path, "rb").read()
    doc = None
    for enc in ("utf-8-sig", "utf-16", "gb18030"):
        try:
            doc = json.loads(raw.decode(enc))
            break
        except (UnicodeDecodeError, ValueError):
            continue
    if doc is None:
        raise SystemExit("无法解析 findings 文件（试过 utf-8/utf-16/gb18030）: %s" % path)
    out = []
    if "findings" in doc:
        for f in doc["findings"]:
            out.append((f.get("file"), int(f.get("line", 0))))
    else:
        for run in doc.get("runs", []):
            for res in run.get("results", []):
                loc = res["locations"][0]["physicalLocation"]
                out.append((loc["artifactLocation"]["uri"], int(loc["region"]["startLine"])))
    return out

def main():
    with open("labels.json", encoding="utf-8") as fh:
        labels = json.load(fh)["vulns"]
    truth = {(v["file"], v["line"]) for v in labels}
    got = set(load_findings(sys.argv[1]))
    tp = len(truth & got)
    fp = len(got - truth)
    fn = len(truth - got)
    recall = tp / (tp + fn) if (tp + fn) else 0.0
    precision = tp / (tp + fp) if (tp + fp) else 0.0
    fp_rate = fp / (tp + fp) if (tp + fp) else 0.0
    print(f"labels={len(truth)} findings={len(got)} TP={tp} FP={fp} FN={fn}")
    print(f"recall={recall:.1%} precision={precision:.1%} false_positive_rate={fp_rate:.1%}")

if __name__ == "__main__":
    main()
