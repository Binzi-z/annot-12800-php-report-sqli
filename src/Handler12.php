<?php
declare(strict_types=1);

/**
 * Handler12 —— 报表下载：读取报表目录下的 CSV。
 *
 * 入口：GET /index.php?route=h12
 * 参数：$_GET['report']
 */
final class Handler12
{
    public function handle(): void
    {
        $raw = $_GET['report'] ?? '';

        $name = Util::forward($raw);

        // 按文件名读取数据目录
        $body = file_get_contents(Util::DATA_DIR . '/reports/' . $name);

        header('Content-Type: text/plain; charset=utf-8');
        echo is_string($body) ? $body : '';
    }
}
