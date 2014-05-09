<?php

/**
 * This is the model class for table "transcoder_presets".
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 *
 * The followings are the available columns in table 'transcoder_presets':
 * @property integer $id
 * @property string $name
 * @property string $video_codec
 * @property integer $video_bitrate
 * @property string $resolution
 * @property string $audio_codec
 * @property integer $audio_bitrate
 * @property integer $audio_channels
 * 
 * TODO: Handle MP4/H.264/AAC
 */
class TranscoderPreset extends CActiveRecord
{

	const AUDIO_CHANNELS_ORIGINAL = 0;
	const AUDO_CODEC_VORBIS = 'vorbis';
	const VIDEO_CODEC_VPX = 'vpx';
	const VIDEO_RESOLUTION_ORIGINAL = 'original';
	const VIDEO_RESOLUTION_480P = '480p';
	const VIDEO_RESOLUTION_576P = '576p';
	const VIDEO_RESOLUTION_720P = '720p';

	/**
	 * @var array valid video codecs
	 */
	public static $validVideoCodecs = array(
		self::VIDEO_CODEC_VPX=>'VP8',
	);

	/**
	 * @var array valid audio codecs
	 */
	public static $validAudioCodecs = array(
		self::AUDO_CODEC_VORBIS=>'Ogg Vorbis',
	);

	/**
	 * @var array valid resolutions
	 */
	public static $validResolutions = array(
		'original'=>'Original',
		'480p'=>'480p',
		'576p'=>'576p',
		'720p'=>'720p',
	);

	/**
	 * @var array valid audio channels
	 */
	public static $validAudioChannels = array(
		self::AUDIO_CHANNELS_ORIGINAL=>'Original',
		2=>'Stereo',
	);

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TranscoderPreset the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transcoder_presets';
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'name'=>'Name',
			'video_codec'=>'Video Codec',
			'video_bitrate'=>'Video Bitrate',
			'resolution'=>'Resolution',
			'audio_codec'=>'Audio Codec',
			'audio_bitrate'=>'Audio Bitrate',
			'audio_channels'=>'Audio Channels',
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, video_codec, video_bitrate, resolution, audio_codec, audio_bitrate, audio_channels', 'required'),
			array('video_codec', 'unique', 'message'=>'There can only be one preset per codec'),
			array('video_codec', 'in', 'range'=>array_keys(self::$validVideoCodecs)),
			array('audio_codec', 'in', 'range'=>array_keys(self::$validAudioCodecs)),
			array('resolution', 'in', 'range'=>array_keys(self::$validResolutions)),
			array('audio_channels', 'in', 'range'=>array_keys(self::$validAudioChannels)),
			array('video_bitrate, audio_bitrate, audio_channels', 'numerical', 'integerOnly'=>true),
			array('name, video_codec, resolution, audio_codec', 'safe'),
		);
	}
	
	/**
	 * Returns the MIME type for the specified preset
	 * @param TranscoderPreset $preset
	 * @return string the MIME type
	 */
	public function getMIMEType()
	{
		switch ($this->video_codec)
		{
			case self::VIDEO_CODEC_VPX:
				return 'video/webm';
		}
	}

}
