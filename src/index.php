<?php

// change the following path if necessary
$yii    = __DIR__ . '/../vendor/yiisoft/yii/framework/yii.php';
$config = __DIR__ . '/protected/config/main.php';

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

// include Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// increase memory limit and execution time
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 120);

require_once $yii;

Yii::createWebApplication($config)->run();
