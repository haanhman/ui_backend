<?php

$backend_settings = array(
    'name' => 'Admin Backend',
    'defaultController' => 'index',
    'components' => array(
        'componentConfig' => array(
            'coreMessages' => array(
                'language' => 'vi'
            )
        ),
        'urlManager' => array(
            'class' => 'BackendUrlManager',
            'urlFormat' => 'path',
            'rules' => array(                
                'backend' => 'index/index',
                'backend/change-password' => 'login/changepassword',
                'backend/login' => 'login/index',
                'backend/<controller>' => '<controller>',
                'backend/<controller>/<action>' => '<controller>/<action>',
            ),
        )
    )
);
return CMap::mergeArray(require(dirname(__FILE__) . '/main.php'), $backend_settings);