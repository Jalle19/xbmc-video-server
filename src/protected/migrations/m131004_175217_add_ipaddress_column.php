<?php

class m131004_175217_add_ipaddress_column extends CDbMigration
{

	public function up()
	{
		// Check if column already exists
		$table = Yii::app()->db->schema->getTable('log');
		$columns = $table->getColumnNames();

		if (!in_array('source_address', $columns))
			$this->addColumn('log', 'source_address', 'VARCHAR(45)');
	}

	public function down()
	{
		$this->dropColumn('log', 'source_address');
	}

}