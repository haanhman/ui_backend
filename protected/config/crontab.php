<?php
$crontab_settings = array(        
);
return CMap::mergeArray(require(dirname(__FILE__) . '/main.php'), $crontab_settings);