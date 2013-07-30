<?php

class m130730_072648_add_pagination_setting extends AddSettingMigration
{

	public function getDefaultValue()
	{
		return 60;
	}

	public function getName()
	{
		return 'pagesize';
	}

}