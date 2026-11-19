<?php
declare(strict_types=1);

/**
 * Handler01 —— DNS 查询：解析域名对应的 A 记录。
 *
 * 入口：GET/POST /index.php?route=h01
 * 参数：$_REQUEST['domain']
 */
final class Handler01
{
    public function handle(): void
    {
        $raw = $_REQUEST['domain'] ?? '';

        $domain = Util::forward($raw);

        // 按参数发起探测命令
        $output = shell_exec("/usr/bin/dig +short " . $domain);

        echo Util::escape(is_string($output) ? $output : '');
    }
}
