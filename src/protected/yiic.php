<?php

// load environment from .env if it exists
// include Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

// change the following paths if necessary
$yiic   = __DIR__ . '/../../vendor/yiisoft/yii/framework/yiic.php';
$config = __DIR__ . '/config/console.php';

require_once($yiic);
