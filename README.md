# 报表查询的 PHP 静态分析闸门

## 现状

`baseline/scan.py` 只做正则匹配：凡是出现 `->query(` / `shell_exec(` /
`file_get_contents(` / `curl_init(` 就报一行。召回够，误报高——decoy 全部中招，
`eval.py` 能算出具体数字（当前样例会得到召回 100%、误报率约 42.9%）。

## 语料

`public/index.php` 是前端控制器，按 `?route=hNN` 分发到 `src/HandlerNN.php`；
handler 从 `$_GET` / `$_POST` / `$_REQUEST` 取参数，经 `Util::forward()` 转发后进入 sink。

- `src/Handler*.php`：14 个 handler，其中 8 个真漏洞、6 个 decoy
- `src/Util.php`：`forward()`（只去空白，不净化）、`escape()`、`allowlistHost()`（白名单）
- `src/Db.php`：mysqli 封装，`$db->query()` 是 SQL sink
- `labels.json`：真漏洞的文件、行号、sink 类别
- `eval.py`：算召回/误报
- `tests/test_baseline.py`：baseline 骨架测试 + labels 行号自检

## decoy 的净化方式

| sink | 净化函数 |
|---|---|
| sql_injection | `mysqli_real_escape_string(Db::link(), $raw)` |
| command_injection | `escapeshellarg($raw)` |
| path_traversal | `basename($raw)` |
| ssrf | `Util::allowlistHost($raw)`（host 白名单） |

## 跑法

```
# 语法检查（逐个文件）
for f in public/index.php src/*.php; do php -l "$f"; done

# baseline 与评测
python baseline/scan.py > findings.json
python eval.py findings.json
python -m pytest tests -q
```

本地起服务（可选）：`php -S 127.0.0.1:8080 -t public`

> Windows PowerShell 5.1 的 `>` 会把重定向写成 UTF-16LE，`eval.py` 已做编码兼容；
> 用 cmd 或 bash 重定向时则是 UTF-8。
