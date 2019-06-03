<?php

class m140915_175502_add_power_off_setting extends AddSettingMigration
{
	public function getDefaultValue()
	{
		return '';
	}

	public function getName()
	{
		return 'allowUserPowerOff';
	}
}