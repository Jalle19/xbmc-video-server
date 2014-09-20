<?php

/**
 * Helper class for determining media information, such as what codecs it uses 
 * etc.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MediaInfoHelper
{

	const VIDEO_CODEC_H264 = 'avc1';
	const AUDIO_CODEC_AAC = 'aac';
	const CONTAINER_MP4 = 'mp4';

	/**
	 * Checks whether the specified will need transcoding in order to be 
	 * playable in browsers. For now it assumes that all H.264/AAC/MP4 files 
	 * are playable directly, which is not strictly true.
	 * @param File $file
	 * @return boolean whether the file needs transcoding
	 */
	public static function needsTranscoding($file)
	{
		if (!self::hasMediaInfo($file->streamdetails))
			return true;

		$videoCodec = $file->streamdetails->video[0]->codec;
		$audioCodec = $file->streamdetails->audio[0]->codec;
		$fileInfo = new SplFileInfo($file->file);

		return !($fileInfo->getExtension() === self::CONTAINER_MP4 &&
				$videoCodec === self::VIDEO_CODEC_H264 &&
				$audioCodec === self::AUDIO_CODEC_AAC);
	}

	/**
	 * Checks whether the stream details contain the necessary media info
	 * @return boolean
	 */
	public static function hasMediaInfo($streamDetails)
	{
		return count($streamDetails->audio) !== 0 &&
			   count($streamDetails->video) !== 0;
	}

}
