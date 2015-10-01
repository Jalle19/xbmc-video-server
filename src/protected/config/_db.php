<?php

// return the configuration for the 'db' application component
return array(
	'connectionString' => 'sqlite:'.__DIR__.'/../data/xbmc-video-server.db',
	'schemaCachingDuration'=>2592000, // 30 days
);

// To use MySQL instead of SQLite, use something like this instead. You will 
// then have to configure the settings below to match your setup and run the 
// file schema.mysql.sql to setup the initial database.
//return array(
//	'connectionString' => 'mysql:host=localhost;dbname=xbmc_video_server',
//	'emulatePrepare' => true,
//	'username' => 'root',
//	'password' => '',
//	'charset' => 'utf8',
//	'schemaCachingDuration' => 3600,
//);
