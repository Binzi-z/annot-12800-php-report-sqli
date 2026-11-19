<?php
declare(strict_types=1);

/**
 * 前端控制器：所有请求走 /index.php?route=hNN。
 *
 * 这里只加载类并分发，不做参数校验；具体参数在各 handler 里读取。
 */

require_once __DIR__ . '/../src/Util.php';
require_once __DIR__ . '/../src/Db.php';
require_once __DIR__ . '/../src/Handler00.php';
require_once __DIR__ . '/../src/Handler01.php';
require_once __DIR__ . '/../src/Handler02.php';
require_once __DIR__ . '/../src/Handler03.php';
require_once __DIR__ . '/../src/Handler04.php';
require_once __DIR__ . '/../src/Handler05.php';
require_once __DIR__ . '/../src/Handler06.php';
require_once __DIR__ . '/../src/Handler07.php';
require_once __DIR__ . '/../src/Handler08.php';
require_once __DIR__ . '/../src/Handler09.php';
require_once __DIR__ . '/../src/Handler10.php';
require_once __DIR__ . '/../src/Handler11.php';
require_once __DIR__ . '/../src/Handler12.php';
require_once __DIR__ . '/../src/Handler13.php';

$route = $_GET['route'] ?? '';

switch ($route) {
    case 'h00':
        (new Handler00())->handle();
        break;
    case 'h01':
        (new Handler01())->handle();
        break;
    case 'h02':
        (new Handler02())->handle();
        break;
    case 'h03':
        (new Handler03())->handle();
        break;
    case 'h04':
        (new Handler04())->handle();
        break;
    case 'h05':
        (new Handler05())->handle();
        break;
    case 'h06':
        (new Handler06())->handle();
        break;
    case 'h07':
        (new Handler07())->handle();
        break;
    case 'h08':
        (new Handler08())->handle();
        break;
    case 'h09':
        (new Handler09())->handle();
        break;
    case 'h10':
        (new Handler10())->handle();
        break;
    case 'h11':
        (new Handler11())->handle();
        break;
    case 'h12':
        (new Handler12())->handle();
        break;
    case 'h13':
        (new Handler13())->handle();
        break;
    default:
        header('Content-Type: text/plain; charset=utf-8');
        echo "unknown route\n";
}
