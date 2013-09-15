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
		// To use MySQL instead of SQLite, uncomment the 'db' section below and 
		// comment out the 'db' section above. You will then have to configure 
		// the settings below to match your setup and run the file 
		// schema.mysql.sql to setup the initial database.
		/*'db'=>array(
			'connectionString'=>'mysql:host=localhost;dbname=xbmc_video_server',
			'emulatePrepare'=>true,
			'username'=>'root',
			'password'=>'',
			'charset'=>'utf8',
		),*/
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