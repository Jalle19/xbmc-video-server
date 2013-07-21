<?php

class m130721_103024_add_settings_table extends CDbMigration
{

	public function up()
	{
		// Don't do anything if the "settings" table already exists
		if (Yii::app()->db->schema->getTable('settings') !== null)
			return;

		// Create the "settings" table
		$this->createTable('settings', array(
			'name'=>'VARCHAR PRIMARY KEY NOT NULL',
			'value'=>'VARCHAR NULL'));

		// Add default values
		$this->insert('settings', array(
			'name'=>'applicationName',
			'value'=>'XBMC Video Server'));
		
		$this->insert('settings', array(
			'name'=>'singleFilePlaylist',
			'value'=>'0'));
	}

	public function down()
	{
		$this->dropTable('settings');
	}

}