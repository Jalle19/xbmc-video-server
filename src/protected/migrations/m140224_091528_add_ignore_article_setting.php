<?php

class m140224_091528_add_ignore_article_setting extends AddSettingMigration
{
	public function getDefaultValue()
	{
		return 'false';
	}

	public function getName()
	{
		return 'ignoreArticle';
	}

}