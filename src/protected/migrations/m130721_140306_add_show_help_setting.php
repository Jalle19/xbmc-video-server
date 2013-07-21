<?php

class m130721_140306_add_show_help_setting extends CDbMigration
{

	public function up()
	{
		$setting = Setting::model()->findByAttributes(array('name'=>'showHelpBlocks'));
		if ($setting !== null)
			return;

		$this->insert('settings', array(
			'name'=>'showHelpBlocks',
			'value'=>'1'));
	}

	public function down()
	{
		$this->delete('settings', 'name = :name', array(':name'=>'showHelpBlocks'));
	}

}