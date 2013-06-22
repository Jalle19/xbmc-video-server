<?php

// change the following path if necessary
$yii=dirname(__FILE__).'/../vendor/yiisoft/yii/framework/yii.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

// include Composer's autoloader
require_once(__DIR__.'/../vendor/autoload.php');

// include yii-configbuilder and assemble the config
$builder = __DIR__
		.'/../vendor/crisu83/yii-configbuilder/helpers/ConfigBuilder.php';

require_once($yii);
require_once($builder);

$config = ConfigBuilder::build(array(
	__DIR__.'/protected/config/main.template.php',
	__DIR__.'/protected/config/main.php'
));

Yii::createWebApplication($config)->run();
