<?php

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'XBMC Video Server',
	'defaultController'=>'movie',

	// preloaded components
	'preload'=>array(
		'log', 
	),
	
	// autoloading model and component classes
	'import'=>require_once('_import.php'),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'bootstrap'=>array(
			'class'=>'bootstrap.components.TbApi',
		),
		// cache for API calls
		'apiCallCache'=>array(
			'class'=>'ApiCallCache',
		),
		// general cache
		'cache'=>array(
			'class'=>'CFileCache',
		),
		'clientScript'=>array(
			'class'=>'GruntClientScript',
			'coreScriptPosition'=>CClientScript::POS_END,
			'packages'=>array(
				'jquery'=>array(
						'baseUrl'=>'//ajax.googleapis.com/ajax/libs/jquery/1.9.1/',
						'js'=>array(YII_DEBUG ? 'jquery.js' : 'jquery.min.js'),
				),
				// we ship these in the compiled script
				'bbq'=>array('js'=>false),
				'history'=>array('js'=>false),
			),
			// list of scripts and styles that we include in our compiled files
			'bundledFiles'=>array(
				'jquery.yiilistview.js',
				'jquery-unveil.min.js',
				'listview/styles.css',
			)
		),
		'backendManager'=>array(
			'class'=>'BackendManager',
		),
		'db'=>array(
			'connectionString' => 'sqlite:'.__DIR__.'/../data/xbmc-video-server.db',
			'schemaCachingDuration'=>2592000, // 30 days
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
			'schemaCachingDuration'=>3600,
		),*/
		'xbmc'=>array(
			'class'=>'XBMC',
		),
		'languageManager'=>array(
			'class'=>'LanguageManager',
		),
		'powerOffManager'=>array(
			'class'=>'PowerOffManager',
		),
		'user'=>array(
			'class'=>'WebUser',
			'allowAutoLogin'=>true,
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'DbLogRoute',
					'logTableName'=>'log',
					'connectionID'=>'db',
				),
				array(
					'class'=>'CFileLogRoute',
				)
			),
		),
		'whitelist'=>array(
			'class'=>'Whitelist',
		)
	),
	
	// application-level parameters
	'params'=>array(
		'minimumBackendVersion'=>13,
	)

);