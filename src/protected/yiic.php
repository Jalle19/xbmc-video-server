<?php

// load environment from .env if it exists
// include Composer's autoloader
require_once __DIR__.'/../../vendor/autoload.php';

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../../vendor/yiisoft/yii/framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';

require_once($yiic);
