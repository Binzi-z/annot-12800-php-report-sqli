<?php
declare(strict_types=1);

/**
 * Handler02 —— 附件查看：按文件名读取上传目录。
 *
 * 入口：GET /index.php?route=h02
 * 参数：$_GET['file']
 */
final class Handler02
{
    public function handle(): void
    {
        $raw = $_GET['file'] ?? '';

        // 只取路径最后一段，去掉目录分隔符
        $clean = basename($raw);
        $name = Util::forward($clean);

        // 按文件名读取数据目录
        $body = file_get_contents(Util::DATA_DIR . '/uploads/' . $name);

        header('Content-Type: text/plain; charset=utf-8');
        echo is_string($body) ? $body : '';
    }
}
