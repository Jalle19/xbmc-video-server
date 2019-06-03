<?php

class m151126_185530_add_user_start_page_column extends AddColumnMigration
{

	protected function getColumnName()
	{
		return 'start_page';
	}

	protected function getColumnType()
	{
		return 'string NULL';
	}

	protected function getTableName()
	{
		return 'user';
	}

}
