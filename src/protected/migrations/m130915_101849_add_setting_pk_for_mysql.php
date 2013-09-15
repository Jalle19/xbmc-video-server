<?php

class m130915_101849_add_setting_pk_for_mysql extends CDbMigration
{

	public function up()
	{
		// Bail out if we are not configured for MySQL
		if (strpos(Yii::app()->db->connectionString, 'sqlite') === 0)
		{
			echo "Not configured to use MySQL, skipping this migration ...\n";
			return true;
		}

		// Try to drop any previous defined primary key (in case someone fixed 
		// this on their own)
		try
		{
			$this->dropPrimaryKey('name', 'settings');
		}
		catch (CDbException $e)
		{
			// ignore the exception, it means the primary key doesn't exist
		}

		$this->addPrimaryKey('PRIMARY', 'settings', 'name');
	}

	public function down()
	{
		echo "m130915_101849_add_setting_pk_for_mysql does not support migration down.\n";
		return false;
	}

}