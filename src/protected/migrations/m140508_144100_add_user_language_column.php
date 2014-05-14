<?php

class m140508_144100_add_user_language_column extends CDbMigration
{

	public function up()
	{
		$table = Yii::app()->db->schema->getTable('user');

		if ($table === null)
			return false;

		// Check if column already exists
		$columns = $table->getColumnNames();

		if (!in_array('language', $columns))
			$this->addColumn('user', 'language', 'string NULL');
	}

	public function down()
	{
		$this->dropColumn('user', 'language');
	}

}
