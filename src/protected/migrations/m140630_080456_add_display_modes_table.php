<?php

class m140630_080456_add_display_modes_table extends CDbMigration
{

	public function up()
	{
		if (Yii::app()->db->schema->getTable('display_mode') !== null)
			return;

		// SQLite cannot add foreign keys after a table has been created so 
		// we'll have to do it in the definition
		$this->createTable('display_mode', array(
			'id'=>'pk',
			'user_id'=>'integer NOT NULL REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE',
			'context'=>'string NOT NULL',
			'mode'=>'string NOT NULL'
		));
	}

	public function down()
	{
		$this->dropTable('display_mode');
	}

}
