<?php

// define aliases
Yii::setPathOfAlias('composer', realpath(__DIR__.'/../../../vendor'));

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	
	'preload'=>array('log'),
	
	'import'=>array(
		'application.models.*',
	),
	
	'components'=>array(
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/xbmc-video-server.db',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);