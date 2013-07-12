<?php

class m130705_222015_multiple_backends extends CDbMigration
{

	public function up()
	{
		// Check if this migration needs to be applied at all
		if (Yii::app()->db->schema->getTable('config') === null)
			return;
		
		// Retrieve the current configuration
		$backend = array();
		$config = Yii::app()->db->createCommand('SELECT * FROM `config`')->queryAll();

		// Turn the result into something usable
		foreach ($config as $row)
			$backend[$row['property']] = $row['value'];

		// Create the "backends" table and drop the old "config" table
		$this->createTable('backend', array(
			'id'=>'INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL',
			'name'=>'VARCHAR NOT NULL',
			'hostname'=>'VARCHAR NOT NULL',
			'port'=>'INTEGER NOT NULL',
			'username'=>'VARCHAR NOT NULL',
			'password'=>'VARCHAR NOT NULL',
			'proxyLocation'=>'VARCHAR',
			'default'=>'INTEGER NOT NULL'
		));

		$this->dropTable('config');

		// Insert the previous configuration as a new backend
		$this->insert('backend', array(
			'name'=>'Default backend',
			'hostname'=>$backend['hostname'],
			'port'=>$backend['port'],
			'username'=>$backend['username'],
			'password'=>$backend['password'],
			'proxyLocation'=>$backend['proxyLocation'],
			'default'=>1,
		));
	}

	public function down()
	{
		echo "m130705_222015_multiple_backends does not support migration down.\n";
		return false;
	}

}