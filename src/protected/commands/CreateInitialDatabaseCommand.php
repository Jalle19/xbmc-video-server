<?php

/**
 * Console command which creates the application database based on the schema 
 * file in the data/ directory
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class CreateInitialDatabaseCommand extends CConsoleCommand
{

	/**
	 * Default action
	 */
	public function actionIndex()
	{
		// Determine which schema to use
		$schemaFile = getenv('USE_MYSQL') === 'true' ? 'schema.mysql.sql' : 'schema.sqlite.sql';
		$schema = file_get_contents(realpath(__DIR__ .'/../data/'.$schemaFile));

		// Execute each command in the schema one by one
		$commands = explode(';', $schema);

		foreach (array_filter($commands) as $command) {
			// MySQL doesn't like empty queries
			$command = trim($command);
			
			if ($command !== '') {
				Yii::app()->db->createCommand(trim($command))->execute();
			}
		}
	}
}
