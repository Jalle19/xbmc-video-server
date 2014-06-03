<?php

class m140603_223918_add_backend_subnet_column extends AddColumnMigration
{

	protected function getColumnName()
	{
		return 'subnetMask';
	}

	protected function getColumnType()
	{
		return 'string NULL';
	}

	protected function getTableName()
	{
		return 'backend';
	}

}
