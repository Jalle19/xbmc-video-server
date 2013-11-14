<?php

class m131004_175217_add_ipaddress_column extends CDbMigration
{

	public function up()
	{
		// Check that the table exists, if not it will be created automatically 
		// later and we don't have to do anything
		$table = Yii::app()->db->schema->getTable('log');

		if ($table === null)
			return true;

		// Check if column already exists
		$columns = $table->getColumnNames();

		if (!in_array('source_address', $columns))
			$this->addColumn('log', 'source_address', 'VARCHAR(45)');
	}

	public function down()
	{
		$this->dropColumn('log', 'source_address');
	}

}