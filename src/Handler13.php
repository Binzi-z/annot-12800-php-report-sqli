<?php
declare(strict_types=1);

/**
 * Handler13 —— 通讯录：按姓名模糊搜索。
 *
 * 入口：GET/POST /index.php?route=h13
 * 参数：$_REQUEST['keyword']
 */
final class Handler13
{
    public function handle(): void
    {
        $raw = $_REQUEST['keyword'] ?? '';

        $pattern = Util::forward($raw);

        // 按条件检索记录
        $db = Db::open();

        $rows = $db->query("SELECT id FROM users WHERE name LIKE '%" . $pattern . "%'");

        echo json_encode($rows, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }
}
