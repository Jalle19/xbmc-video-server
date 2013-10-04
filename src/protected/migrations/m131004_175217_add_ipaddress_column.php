<?php

class m131004_175217_add_ipaddress_column extends CDbMigration
{

	public function up()
	{
		// Check if column already exists
		$table = Yii::app()->db->schema->getTable('log');
		$columns = $table->getColumnNames();

		if (!isset($columns['source_address']))
			$this->addColumn('log', 'source_address', 'VARCHAR(45)');
	}

	public function down()
	{
		$this->dropColumn('log', 'source_address');
	}

}