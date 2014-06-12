<?php

class m131004_175217_add_ipaddress_column extends AddColumnMigration
{

	protected function getColumnName()
	{
		return 'source_address';
	}

	protected function getColumnType()
	{
		return 'VARCHAR(45)';
	}

	protected function getTableName()
	{
		return 'log';
	}
	
	public function up()
	{
		// It's okay if the table doesn't exist, it is created correctly the 
		// first time the application is run
		if (Yii::app()->db->schema->getTable($this->getTableName()) === null)
			return true;

		return parent::up();
	}

}
