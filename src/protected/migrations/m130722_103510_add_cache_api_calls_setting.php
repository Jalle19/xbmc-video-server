<?php

class m130722_103510_add_cache_api_calls_setting extends AddSettingMigration
{

	public function getDefaultValue()
	{
		return '0';
	}

	public function getName()
	{
		return 'cacheApiCalls';
	}

}