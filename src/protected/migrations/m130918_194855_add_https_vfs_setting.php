<?php

class m130918_194855_add_https_vfs_setting extends AddSettingMigration
{

	public function getDefaultValue()
	{
		return "0";
	}

	public function getName()
	{
		return "useHttpsForVfsUrls";
	}

}