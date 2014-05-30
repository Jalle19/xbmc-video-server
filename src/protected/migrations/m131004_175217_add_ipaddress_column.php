<?php

class m131004_175217_add_ipaddress_column extends AddColumnMigration
{

	protected function getColumnName()
	{
		return 'source_address';
	}

	protected function getColumnType()
	{
		return 'VARCHAR(45)';
	}

	protected function getTableName()
	{
		return 'log';
	}

}
