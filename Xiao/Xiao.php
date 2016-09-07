<?php

defined('XIAO_PATH') or define('XIAO_PATH', __DIR__.DIRECTORY_SEPARATOR);
defined('APP_NAME') or define('APP_NAME', 'demo');
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).DIRECTORY_SEPARATOR);
defined('EXT') or define('EXT', '.php');
defined('DEFAULT_TIMEZONE') or define('DEFAULT_TIMEZONE', 'PRC');
defined('DEBUG') or define('DEBUG', false);

define('DB_NAME', 'game');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');

date_default_timezone_set(DEFAULT_TIMEZONE);

//    var_dump(XIAO_PATH);
require_once(XIAO_PATH.'Core.php');
\Xiao\Core::start();