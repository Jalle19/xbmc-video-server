<?php

class m140707_054954_add_application_subtitle_setting extends AddSettingMigration
{
	public function getDefaultValue()
	{
		return Yii::t('Misc', 'Free your library');
	}

	public function getName()
	{
		return 'applicationSubtitle';
	}

}