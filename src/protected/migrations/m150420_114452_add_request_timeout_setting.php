<?php

class m150420_114452_add_request_timeout_setting extends AddSettingMigration
{

	public function getDefaultValue()
	{
		return 30;
	}

	public function getName()
	{
		return 'requestTimeout';
	}

}
