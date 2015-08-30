<?php
ini_set('memory_limit', '-1');
date_default_timezone_set('Asia/Bangkok');
ini_set('session.cookie_lifetime', 3600 * 5); //thoi gian time out
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

global $mysql_config;
$mysql_config = array(
    'host' => '127.0.0.1',
    'username' => 'eduuser',
    'password' => 'Eadux23X',
    'dbname' => 'admin_backend'
);

//so ban ghi tren 1 trang
define('PAGE_SIZE', 20);

define('ROOT_PATH', dirname(__FILE__));
define('FRAMEWORK_PATH', ROOT_PATH . '/framework');
define('YII_DEBUG', $_GET['bug'] == 1 ? TRUE : FALSE);
if (YII_DEBUG == TRUE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}
require_once ROOT_PATH . '/lib/function.php';
// include Yii bootstrap file
if (extension_loaded('apc') && ini_get('apc.enabled')) {
    require_once(FRAMEWORK_PATH . '/yiilite.php');
} else {
    require_once(FRAMEWORK_PATH . '/yii.php');
}