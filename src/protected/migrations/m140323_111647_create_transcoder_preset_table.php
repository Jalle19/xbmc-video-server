<?php

class m140323_111647_create_transcoder_preset_table extends CDbMigration
{

	public function up()
	{
		// Don't do anything if the table already exists
		if (Yii::app()->db->schema->getTable('transcoder_presets') !== null)
			return;

		// Create the table
		$this->createTable('transcoder_presets', array(
			'id'=>'pk',
			'name'=>'VARCHAR',
			'video_codec'=>'VARCHAR',
			'video_bitrate'=>'INT',
			'resolution'=>'VARCHAR',
			'audio_codec'=>'VARCHAR',
			'audio_bitrate'=>'INT',
			'audio_channels'=>'INT'));

		// Add default values
		$this->insert('transcoder_presets', array(
			'name'=>'WebM',
			'video_codec'=>'libvpx',
			'video_bitrate'=>'1500',
			'resolution'=>'original',
			'audio_codec'=>'libvorbis',
			'audio_bitrate'=>'128',
			'audio_channels'=>'2'));
	}

	public function down()
	{
		$this->dropTable('transcoder_presets');
	}

}
