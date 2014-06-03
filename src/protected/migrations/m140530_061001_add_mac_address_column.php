<?php

class m140530_061001_add_mac_address_column extends AddColumnMigration
{

	protected function getColumnName()
	{
		return 'macAddress';
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
