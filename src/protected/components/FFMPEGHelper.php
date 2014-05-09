<?php

use Symfony\Component\Process\ProcessUtils;

/**
 * Helper for generating the FFMPEG command needed for transcoding
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class FFMPEGHelper
{

	/**
	 * @var TranscoderPreset the preset we're currently working on
	 */
	private $_preset;

	/**
	 * Class constructor
	 * @param TranscoderPreset $preset the preset we're currently working on
	 */
	public function __construct($preset)
	{
		$this->_preset = $preset;
	}

	/**
	 * Returns the ffmpeg arguments which corresponds to the specified preset
	 * @return string the command-line arguments
	 */
	public function getTranscoderArguments()
	{
		// TODO: Handle resolution
		$args = array(
			'-c:v'=>$this->getVideoCodec(),
			'-b:v'=>$this->_preset->video_bitrate.'k',
			'-c:a'=>$this->getAudioCodec(),
			'-b:a'=>$this->_preset->audio_bitrate.'k',
		);

		// Ignore -ac if we're leaving the audio untouched
		if ($this->_preset->audio_channels !== TranscoderPreset::AUDIO_CHANNELS_ORIGINAL)
			$args['-ac'] = $this->_preset->audio_channels;

		// Format-specific options
		switch ($this->_preset->video_codec)
		{
			case TranscoderPreset::VIDEO_CODEC_VPX:
				$args['-deadline'] = 'realtime';
				$args['-f'] = 'webm';
				break;
		}

		// Encode in realtime
		$args[] = '-re';

		// Convert to string
		$argString = '';

		foreach ($args as $name=> $value)
			if (is_int($name))
				$argString .= $value.' ';
			else
				$argString .= $name.' '.$value.' ';

		return trim($argString);
	}

	/**
	 * Mangles the source URL so it works with ffmpeg/avconv
	 * @param string $url the source URL
	 * @return string the mangled URL
	 */
	public function escapeSource($url)
	{
		return str_replace(' ', '%20', ProcessUtils::escapeArgument($url));
	}

	/**
	 * @return string the video codec string
	 */
	private function getVideoCodec()
	{
		switch ($this->_preset->video_codec)
		{
			case TranscoderPreset::VIDEO_CODEC_VPX:
				return 'libvpx';
		}
	}

	/**
	 * @return string the audio codec string
	 */
	private function getAudioCodec()
	{
		switch ($this->_preset->audio_codec)
		{
			case TranscoderPreset::AUDO_CODEC_VORBIS:
				return 'libvorbis';
		}
	}

}
