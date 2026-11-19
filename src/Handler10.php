<?php
declare(strict_types=1);

/**
 * Handler10 —— 节点巡检：查询主机名解析结果。
 *
 * 入口：POST /index.php?route=h10
 * 参数：$_POST['addr']
 */
final class Handler10
{
    public function handle(): void
    {
        $raw = $_POST['addr'] ?? '';

        $addr = Util::forward($raw);

        // 按参数发起探测命令
        $output = shell_exec("nslookup " . $addr);

        echo Util::escape(is_string($output) ? $output : '');
    }
}
