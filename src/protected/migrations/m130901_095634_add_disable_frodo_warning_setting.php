<?php

class m130901_095634_add_disable_frodo_warning_setting extends AddSettingMigration
{

	public function getDefaultValue()
	{
		return '0';
	}

	public function getName()
	{
		return 'disableFrodoWarning';
	}

}