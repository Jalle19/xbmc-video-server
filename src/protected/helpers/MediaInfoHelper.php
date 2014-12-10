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

	const VIDEO_CODEC_AVC1 = 'avc1';
	const VIDEO_CODEC_H264 = 'h264';
	const AUDIO_CODEC_AAC = 'aac';
	const CONTAINER_MKV = 'mkv';
	const CONTAINER_MP4 = 'mp4';
	
	/**
	 * @var array list of codecs that are supported by major browsers
	 */
	private static $nativeVideoCodecs = array(
		self::VIDEO_CODEC_AVC1,
		self::VIDEO_CODEC_H264
	);
	
	/**
	 * @var array list of container types that are supported by major browsers
	 */
	private static $nativeContainers = array(
		self::CONTAINER_MKV, // Chrome only
		self::CONTAINER_MP4,
	);
	
	/**
	 * @var array list of file extension that cannot be streamed
	 */
	private static $nonStreamableExtension = array(
		'iso', 'img', 'bdmv', 'ifo',
	);
	
	/**
	 * @var File the media file
	 */
	private $_file;
	
	/**
	 * Class constructor
	 * @param File $file the media file
	 */
	public function __construct($file)
	{
		$this->_file = $file;
	}

	/**
	 * Checks whether the specified will need transcoding in order to be 
	 * playable in browsers. For now it assumes that all H.264/AAC/MP4 files 
	 * are playable directly, which is not strictly true.
	 * @return boolean whether the file needs transcoding
	 */
	public function needsTranscoding()
	{
		// If stream details aren't available we'll just have to go on the 
		// file extension
		if (!$this->hasMediaInfo())
			return !$this->hasNativeContainer();

		$videoCodec = $this->_file->streamdetails->video[0]->codec;
		$audioCodec = $this->_file->streamdetails->audio[0]->codec;

		return !($this->hasNativeContainer() &&
				in_array($videoCodec, self::$nativeVideoCodecs) &&
				$audioCodec === self::AUDIO_CODEC_AAC);
	}

	/**
	 * Checks whether the stream details contain the necessary media info
	 * @return boolean
	 */
	public function hasMediaInfo()
	{
		return count($this->_file->streamdetails->audio) !== 0 &&
			   count($this->_file->streamdetails->video) !== 0;
	}
	
	/**
	 * Checks whether the current file is streamable. All files are streamable 
	 * except DVD and BluRay images (or folder structures)
	 * @return boolean whether the file is streamable
	 */
	public function isStreamable()
	{
		foreach ($this->_file->getItemLinks() as $link)
		{
			// Compare the extension against the blacklist
			$fileInfo = new SplFileInfo($link->url);
			$extension = strtolower($fileInfo->getExtension());

			if (in_array($extension, self::$nonStreamableExtension))
				return false;
		}

		return true;
	}

	/**
	 * @return boolean whether the file uses a container that browsers can 
	 * generally play natively
	 */
	private function hasNativeContainer()
	{
		$fileInfo = new SplFileInfo($this->_file->file);
		return in_array($fileInfo->getExtension(), self::$nativeContainers);
	}

	/**
	 * Returns the MIME type of the specified file
	 * @param string $file filename or URL
	 * @return string the MIME type, or null if it could not be determined
	 */
	public static function getMIMEType($file)
	{
		$fileInfo = new SplFileInfo($file);
		
		switch ($fileInfo->getExtension())
		{
			case self::CONTAINER_MP4:
				return 'video/mp4';
			// Ignore MKV on purpose, Chrome refuses to play MKV files when 
			// MIME type is set
		}
		
		return null;
	}

}
