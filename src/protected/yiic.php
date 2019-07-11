<?php

// include Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

// load environment from .env if it exists
if (file_exists(__DIR__ . '/../../.env')) {
	$dotenv = \Dotenv\Dotenv::create(__DIR__ . '/../..');
	$dotenv->load();
}

// change the following paths if necessary
$yiic   = __DIR__ . '/../../vendor/yiisoft/yii/framework/yiic.php';
$config = __DIR__ . '/config/console.php';

require_once($yiic);
