<?php
declare(strict_types=1);

/**
 * Handler08 —— 网络诊断：从运维面板发起 ping 探测。
 *
 * 入口：POST /index.php?route=h08
 * 参数：$_POST['host']
 */
final class Handler08
{
    public function handle(): void
    {
        $raw = $_POST['host'] ?? '';

        // 整个参数加单引号，避免被 shell 拆词
        $clean = escapeshellarg($raw);
        $target = Util::forward($clean);

        // 按参数发起探测命令
        $output = shell_exec("ping -c 1 " . $target);

        echo Util::escape(is_string($output) ? $output : '');
    }
}
