<?php

Yii::import('composer.crisu83.yii-consoletools.commands.PermissionsCommand');

/**
 * Sets the correct permissions for certain files and directories
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class SetPermissionsCommand extends PermissionsCommand
{

	/**
	 * Initializes the command. Here we define the permissions that need to be 
	 * set and the base path under which the specified files/directories are 
	 * located.
	 */
	public function init()
	{
		$this->permissions = array(
			'assets'=>array('mode'=>0777),
			'images/image-cache'=>array('mode'=>0777),
			'protected/data'=>array('mode'=>0755),
			'protected/data/xbmc-video-server.db'=>array('mode'=>0666),
			'protected/runtime'=>array('mode'=>0777),
		);

		$this->basePath = realpath(__DIR__.'/../../');
	}

}