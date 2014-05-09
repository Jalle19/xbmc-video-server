<?php

class m140508_144100_add_user_language_column extends CDbMigration
{

	public function up()
	{
		$this->addColumn('user', 'language', "string NULL");
	}

	public function down()
	{
		$this->dropColumn('user', 'language');
	}

}
