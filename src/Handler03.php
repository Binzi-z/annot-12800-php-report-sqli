<?php
declare(strict_types=1);

/**
 * Handler03 —— 素材同步：按地址下载远端清单。
 *
 * 入口：POST /index.php?route=h03
 * 参数：$_POST['url']
 */
final class Handler03
{
    public function handle(): void
    {
        $raw = $_POST['url'] ?? '';

        $endpoint = Util::forward($raw);

        // 按地址拉取远端内容
        $body = file_get_contents($endpoint);

        header('Content-Type: text/plain; charset=utf-8');
        echo is_string($body) ? $body : '';
    }
}
