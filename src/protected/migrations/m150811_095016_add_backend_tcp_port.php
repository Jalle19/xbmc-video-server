<?php

class m150811_095016_add_backend_tcp_port extends AddColumnMigration
{

	protected function getColumnName()
	{
		return 'tcp_port';
	}

	protected function getColumnType()
	{
		return 'int NOT NULL DEFAULT 9090';
	}

	protected function getTableName()
	{
		return 'backend';
	}

}
