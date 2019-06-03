<?php

class m160722_105938_add_enable_actor_typeahead_setting extends AddSettingMigration
{

	/**
	 * @inheritdoc
	 */
	public function getName()
	{
		return 'enableActorTypeahead';
	}


	/**
	 * @inheritdoc
	 */
	public function getDefaultValue()
	{
		return '0';
	}

}
