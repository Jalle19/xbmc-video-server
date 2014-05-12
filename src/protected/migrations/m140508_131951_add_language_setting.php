<?php

class m140508_131951_add_language_setting extends AddSettingMigration
{

	public function getDefaultValue()
	{
		return 'en';
	}

	public function getName()
	{
		return 'language';
	}

}
