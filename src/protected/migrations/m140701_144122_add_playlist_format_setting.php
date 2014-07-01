<?php

class m140701_144122_add_playlist_format_setting extends AddSettingMigration
{

	public function getDefaultValue()
	{
		return 'm3u';
	}

	public function getName()
	{
		return 'playlistFormat';
	}

}
