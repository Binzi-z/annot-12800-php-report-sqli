<?php
declare(strict_types=1);

/**
 * Handler09 —— 权限核对：按用户名查角色。
 *
 * 入口：GET /index.php?route=h09
 * 参数：$_GET['username']
 */
final class Handler09
{
    public function handle(): void
    {
        $raw = $_GET['username'] ?? '';

        // 先把单引号转义，再拼进查询
        $clean = mysqli_real_escape_string(Db::link(), $raw);
        $username = Util::forward($clean);

        // 按条件检索记录
        $db = Db::open();

        $rows = $db->query("SELECT id, role FROM accounts WHERE username = '" . $username . "'");

        echo json_encode($rows, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }
}
