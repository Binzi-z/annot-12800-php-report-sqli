<?php
declare(strict_types=1);

/**
 * Handler05 —— 远程配置拉取：从配置服务读取 JSON。
 *
 * 入口：POST /index.php?route=h05
 * 参数：$_POST['endpoint']
 */
final class Handler05
{
    public function handle(): void
    {
        $raw = $_POST['endpoint'] ?? '';

        $endpoint = Util::forward($raw);

        // 按地址拉取远端内容
        $body = file_get_contents($endpoint);

        header('Content-Type: text/plain; charset=utf-8');
        echo is_string($body) ? $body : '';
    }
}
