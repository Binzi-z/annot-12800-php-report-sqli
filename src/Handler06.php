<?php
declare(strict_types=1);

/**
 * Handler06 —— 节点巡检：查询主机名解析结果。
 *
 * 入口：POST /index.php?route=h06
 * 参数：$_POST['addr']
 */
final class Handler06
{
    public function handle(): void
    {
        $raw = $_POST['addr'] ?? '';

        // 整个参数加单引号，避免被 shell 拆词
        $clean = escapeshellarg($raw);
        $addr = Util::forward($clean);

        // 按参数发起探测命令
        $output = shell_exec("nslookup " . $addr);

        echo Util::escape(is_string($output) ? $output : '');
    }
}
