<?php
declare(strict_types=1);

/**
 * 通用工具：请求参数转发、输出转义、SSRF 白名单。
 *
 * 注意：forward() 只做首尾去空白，不承担任何安全职责；
 * 需要转义或校验时，由调用方按场景自行处理。
 */
final class Util
{
    /** 报表、模板、上传文件的根目录。 */
    public const DATA_DIR = '/srv/data';

    /** 允许访问的内网主机白名单（仅 SSRF 净化使用）。 */
    private const ALLOWED_HOSTS = ['api.internal', 'feed.internal', 'storage.internal'];

    /**
     * 转发请求参数：只去掉首尾空白，空值兜底为空串。
     */
    public static function forward(string $value): string
    {
        return trim($value);
    }

    /**
     * 输出转义：把上游内容回显到页面前先做 HTML 转义。
     */
    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * 只允许访问白名单主机：非白名单 URL 一律替换成占位地址。
     */
    public static function allowlistHost(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (is_string($host) && in_array(strtolower($host), self::ALLOWED_HOSTS, true)) {
            return $url;
        }
        return 'https://api.internal/blocked';
    }
}
