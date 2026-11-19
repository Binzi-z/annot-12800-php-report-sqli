<?php
declare(strict_types=1);

/**
 * Db —— mysqli 的薄封装。
 *
 * handler 里统一通过 Db 实例的 query() 方法执行 SQL，查询结果以关联数组返回。
 * 连接参数来自部署时的环境变量，未做任何 SQL 预处理（历史遗留）。
 */
final class Db
{
    private mysqli $conn;

    private function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /**
     * 复用同一个连接，类里只保留一个 mysqli 实例。
     */
    public static function link(): mysqli
    {
        static $link = null;
        if ($link === null) {
            $link = new mysqli(
                getenv('DB_HOST') ?: '127.0.0.1',
                getenv('DB_USER') ?: 'app',
                getenv('DB_PASS') ?: 'app',
                getenv('DB_NAME') ?: 'appdb'
            );
            if ($link->connect_errno !== 0) {
                throw new RuntimeException('db connect failed: ' . $link->connect_error);
            }
            $link->set_charset('utf8mb4');
        }
        return $link;
    }

    public static function open(): self
    {
        return new self(self::link());
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function query(string $sql): array
    {
        $res = mysqli_query($this->conn, $sql);
        if ($res === false) {
            return [];
        }
        $rows = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $rows[] = $row;
        }
        mysqli_free_result($res);
        return $rows;
    }
}
