<?php
require_once 'define.php';
$config = ROOT_PATH . '/protected/config/fontend.php';
// create a Web application instance and run
Yii::createWebApplication($config)->runEnd('fontend');