<?php
define('HOME',__DIR__ . '/../');
require_once HOME . 'system/config.php';
require_once HOME . 'system/helper.php';

\system\model\role::injectDatabase(\system\registry\registry::get('dbConn'));
\system\model\user::injectDatabase(\system\registry\registry::get('dbConn'));
\system\model\wordlist::injectDatabase(\system\registry\registry::get('dbConn'));
\system\model\word::injectDatabase(\system\registry\registry::get('dbConn'));
\system\model\group::injectDatabase(\system\registry\registry::get('dbConn'));
\system\service\authenticate\auth::injectRedis(\system\registry\registry::get('rdb'));
$u = \system\model\user::getInstance();
\system\service\authenticate\auth::injectUserModel($u);

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 'Off');

require_once HOME . 'app/routes.php';