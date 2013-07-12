<?php

/**
 * Console command which creates the application database based on the schema 
 * file in the data/ directory
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class CreateInitialDatabaseCommand extends CConsoleCommand
{

	/**
	 * Default action
	 */
	public function actionIndex()
	{
		$schema = file_get_contents(realpath(__DIR__
						.'/../data/schema.sqlite.sql'));

		// Execute each command in the schema one by one
		$commands = explode(';', $schema);

		foreach (array_filter($commands) as $command)
			Yii::app()->db->createCommand(trim($command))->execute();
	}

}