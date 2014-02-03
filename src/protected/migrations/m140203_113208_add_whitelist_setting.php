<?php

class m140203_113208_add_whitelist_setting extends AddSettingMigration
{
	
	public function getDefaultValue()
	{
		return '';
	}

	public function getName()
	{
		return 'whitelist';
	}
	
}