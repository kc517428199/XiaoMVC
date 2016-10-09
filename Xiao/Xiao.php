<?php

defined('APP_NAME') or define('APP_NAME', 'demo');
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).DIRECTORY_SEPARATOR);
defined('APP_CONTROLLER') or define('APP_CONTROLLER', APP_PATH .'controller' . DIRECTORY_SEPARATOR);
defined('APP_MODEL') or define('APP_MODEL', APP_PATH .'model' . DIRECTORY_SEPARATOR);
defined('APP_VIEW') or define('APP_VIEW', APP_PATH .'view' . DIRECTORY_SEPARATOR);
defined('APP_RUNTIME') or define('APP_RUNTIME', APP_PATH .'runtime' . DIRECTORY_SEPARATOR);
defined('APP_LOG') or define('APP_LOG', APP_RUNTIME .'log' . DIRECTORY_SEPARATOR);
defined('APP_CACHE') or define('APP_CACHE', APP_RUNTIME .'cache' . DIRECTORY_SEPARATOR);
defined('APP_SESSION') or define('APP_SESSION', APP_RUNTIME .'session' . DIRECTORY_SEPARATOR);
defined('APP_CONFIG') or define('APP_CONFIG', APP_PATH .'config' . DIRECTORY_SEPARATOR);

defined('DEFAULT_TIMEZONE') or define('DEFAULT_TIMEZONE', 'PRC');
defined('DEBUG') or define('DEBUG', false);
defined('DISPATCH_TYPE') or define('DISPATCH_TYPE', 1);

defined('XIAO_PATH') or define('XIAO_PATH', __DIR__.DIRECTORY_SEPARATOR);
defined('EXT') or define('EXT', '.php');


define('DB_NAME', 'game');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');

if(DEBUG) {
    !ini_get('display_errors')&&ini_set('display_errors', 'On');
} else {
    ini_set('display_errors', 'Off');
}

date_default_timezone_set(DEFAULT_TIMEZONE);


require_once(XIAO_PATH.'Core.php');
\Xiao\Core::start();