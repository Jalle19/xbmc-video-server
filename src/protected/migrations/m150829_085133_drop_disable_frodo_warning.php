<?php

class m150829_085133_drop_disable_frodo_warning extends CDbMigration
{

	public function up()
	{
		$this->delete('settings', 'name = :name', array(
			':name' => 'disableFrodoWarning'));
	}

	public function down()
	{
		$this->insert('settings', array(
			'name' => 'disableFrodoWarning',
			'value' => '0'));
	}

}
