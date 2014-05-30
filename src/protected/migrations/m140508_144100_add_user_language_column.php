<?php

class m140508_144100_add_user_language_column extends AddColumnMigration
{

	protected function getColumnName()
	{
		return 'language';
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
