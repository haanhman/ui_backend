<?php

global $mysql_config;
$settings = array(
    'name' => 'Admin UI',
    'import' => array(
        'application.components.*',
    ),
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',    
    'components' => array(        
        'coreMessages' => array(
            'basePath' => null,
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'caseSensitive' => false,
        ),
        'db' => array(
            'class' => 'system.db.CDbConnection',
            'connectionString' => 'mysql:host=' . $mysql_config['host'] . ';dbname=' . $mysql_config['dbname'],
            'emulatePrepare' => true,
            'username' => $mysql_config['username'],
            'password' => $mysql_config['password'],
            'tablePrefix' => 'tbl_',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
            'schemaCachingDuration' => 3600
        )
    ),
    'behaviors' => array('class' => 'application.components.WebApplicationEndBehavior'),
    'preload' => array('log')
);

if (PROJECT_ENVIRONMENT == 'online') {
//cau hinh cache DB
    $settings['components']['cache'] = array(
        'class' => 'system.caching.CFileCache',
        'cachePath' => ROOT_PATH . '/protected/runtime/cache/'
    );
}
if (YII_DEBUG == TRUE) {
    $settings['components']['log'] = array(
        'class' => 'CLogRouter',
        'routes' => array(
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'error, warning',
            ),
            array(
                'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                'ipFilters' => array('*'),
            )
        )
    );
}

return $settings;
