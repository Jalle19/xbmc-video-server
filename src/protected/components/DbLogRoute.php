<?php

/**
 * Database log route. We override parent in order to change the "logtime" 
 * column to a DATETIME which makes filtering feasible.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class DbLogRoute extends CDbLogRoute
{

	protected function createLogTable($db, $tableName)
	{
		$db->createCommand()->createTable($tableName, array(
			'id'=>'pk',
			'level'=>'varchar(128)',
			'category'=>'varchar(128)',
			'logtime'=>'datetime',
			'message'=>'text',
		));
	}

	protected function processLogs($logs)
	{
		$command = $this->getDbConnection()->createCommand();
		foreach ($logs as $log)
		{
			$command->insert($this->logTableName, array(
				'level'=>$log[1],
				'category'=>$log[2],
				'logtime'=>date('Y-m-d H:i:s', (int)$log[3]),
				'message'=>$log[0],
			));
		}
	}

}