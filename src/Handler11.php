<?php
declare(strict_types=1);

/**
 * Handler11 —— 账号详情：按邮箱反查用户 ID。
 *
 * 入口：POST /index.php?route=h11
 * 参数：$_POST['email']
 */
final class Handler11
{
    public function handle(): void
    {
        $raw = $_POST['email'] ?? '';

        // 先把单引号转义，再拼进查询
        $clean = mysqli_real_escape_string(Db::link(), $raw);
        $email = Util::forward($clean);

        // 按条件检索记录
        $db = Db::open();

        $rows = $db->query("SELECT id, email FROM users WHERE email = '" . $email . "'");

        echo json_encode($rows, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }
}
