<?php
declare(strict_types=1);

/**
 * Handler07 —— 对账单导出：读取归档目录里的文件。
 *
 * 入口：POST /index.php?route=h07
 * 参数：$_POST['attachment']
 */
final class Handler07
{
    public function handle(): void
    {
        $raw = $_POST['attachment'] ?? '';

        $name = Util::forward($raw);

        // 按文件名读取数据目录
        $body = file_get_contents(Util::DATA_DIR . '/archive/' . $name);

        header('Content-Type: text/plain; charset=utf-8');
        echo is_string($body) ? $body : '';
    }
}
