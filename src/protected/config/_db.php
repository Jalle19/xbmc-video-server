<?php

// Return SQLite configuration unless the USE_MYSQL environment variable is set to true (mostly for advanced Docker 
// usage)
if (getenv('USE_MYSQL') !== 'true')
{
	return [
		'connectionString'      => 'sqlite:' . __DIR__ . '/../data/xbmc-video-server.db',
		'schemaCachingDuration' => 0, // 30 days
	];
}

// MySQL configuration. Use schema.mysql.sql to setup the initial database
$hostname = getenv('MYSQL_HOSTNAME');
$database = getenv('MYSQL_DATABASE');
$user     = getenv('MYSQL_USERNAME');
$password = getenv('MYSQL_PASSWORD');

return [
	'connectionString'      => "mysql:host=$hostname;dbname=$database",
	'emulatePrepare'        => true,
	'username'              => $user,
	'password'              => $password,
	'charset'               => 'utf8',
	'schemaCachingDuration' => 3600,
];
