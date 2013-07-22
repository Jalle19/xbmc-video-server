<?php

class m130721_140306_add_show_help_setting extends AddSettingMigration
{

	public function getDefaultValue()
	{
		return '1';
	}

	public function getName()
	{
		return 'showHelpBlocks';
	}

}