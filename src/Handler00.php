<?php
declare(strict_types=1);

/**
 * Handler00 —— 后台账号检索：按用户名查用户列表。
 *
 * 入口：GET /index.php?route=h00
 * 参数：$_GET['q']
 */
final class Handler00
{
    public function handle(): void
    {
        $raw = $_GET['q'] ?? '';

        $keyword = Util::forward($raw);

        // 按条件检索记录
        $db = Db::open();

        $rows = $db->query("SELECT id, name FROM users WHERE name = '" . $keyword . "'");

        echo json_encode($rows, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }
}
