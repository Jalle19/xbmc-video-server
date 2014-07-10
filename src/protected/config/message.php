<?php

/* 
 * Configuration file for the "yiic message" command which generates translation 
 * files
 */
return array(
	'sourcePath'=>__DIR__.DIRECTORY_SEPARATOR.'..',
	'messagePath'=>__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'messages',
	'fileTypes'=>array('php'),
	
	// Change this to generate files for new languages
	'languages'=>array('fr', 'de'),
);
