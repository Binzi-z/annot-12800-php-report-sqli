<?php
declare(strict_types=1);

/**
 * Handler04 —— 订阅源抓取：拉取第三方 RSS。
 *
 * 入口：GET /index.php?route=h04
 * 参数：$_GET['feed']
 */
final class Handler04
{
    public function handle(): void
    {
        $raw = $_GET['feed'] ?? '';

        // 只放行白名单内的主机，其余替换成占位地址
        $clean = Util::allowlistHost($raw);
        $endpoint = Util::forward($clean);

        // 按地址拉取远端内容
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $body = curl_exec($ch);
        curl_close($ch);

        header('Content-Type: text/plain; charset=utf-8');
        echo is_string($body) ? $body : '';
    }
}
