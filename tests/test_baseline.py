"""骨架测试：baseline 召回要够；误报是待改进点；labels 行号必须落在 sink 上。"""
import glob
import json, re, shutil, subprocess, sys

import pytest

SINK_RE = re.compile(r"(->query\s*\(|shell_exec\s*\(|file_get_contents\s*\(|curl_init\s*\()")


def _labels():
    with open("labels.json", encoding="utf-8") as fh:
        return json.load(fh)["vulns"]


def _scan():
    out = subprocess.run(
        [sys.executable, "baseline/scan.py"],
        capture_output=True, text=True, check=True, encoding="utf-8",
    )
    return {(f["file"], f["line"]) for f in json.loads(out.stdout)["findings"]}


def test_labels_exist():
    assert json.load(open("labels.json", encoding="utf-8"))["total"] > 0


def test_baseline_recall_is_high():
    truth = {(v["file"], v["line"]) for v in _labels()}
    assert len(truth & _scan()) >= len(truth) * 0.9, "baseline 至少覆盖九成标注"


def test_baseline_has_false_positives():
    truth = {(v["file"], v["line"]) for v in _labels()}
    assert len(_scan() - truth) > 0, "baseline 必然有误报，这是要改的地方"


def test_labels_point_at_sink_lines():
    cache = {}
    for v in _labels():
        if v["file"] not in cache:
            with open(v["file"], encoding="utf-8") as fh:
                cache[v["file"]] = fh.read().splitlines()
        line = cache[v["file"]][v["line"] - 1]
        assert SINK_RE.search(line), "%s:%d 不是 sink 调用：%s" % (v["file"], v["line"], line)


def test_php_syntax():
    php = shutil.which("php")
    if not php:
        pytest.skip("php 不在 PATH，跳过语法检查")
    files = ["public/index.php", "src/Util.php", "src/Db.php"]
    files += sorted(p.replace("\\", "/") for p in glob.glob("src/Handler*.php"))
    for f in files:
        r = subprocess.run([php, "-l", f], capture_output=True, text=True, encoding="utf-8")
        assert r.returncode == 0, r.stdout + r.stderr
