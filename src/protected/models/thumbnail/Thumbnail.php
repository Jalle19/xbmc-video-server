<?php

/**
 * Represents a thumbnail. When used as a string the object will return the URL 
 * to a cached copy of the thumbnail if one exists, otherwise it will return 
 * the URL to ThumbnailController which generates the cached copy, thus the 
 * next time the thumbnail is rendered, the cached copy will be returned as the 
 * URL.
 *
 * @see ImageCache
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class Thumbnail extends AbstractThumbnail
{

	const SIZE_VERY_SMALL = 70;
	const SIZE_SMALL = 116;
	const SIZE_MEDIUM = 160;
	const SIZE_LARGE = 434;
	
	/**
	 * Prefix used for storing temporary files
	 */
	const TEMP_FILE_PREFIX = 'xbmc-video-server-thumbnail';
	
	/**
	 * @var int the thumbnail size
	 */
	private $_size;

	/**
	 * @var ImageCache the cache
	 */
	private $_cache;

	/**
	 * Class constructor
	 * @param string $thumbnailPath the thumbnail path.
	 * @param int $size the thumbnail size. Defaults to SIZE_SMALL and can be 
	 * changed afterwards
	 */
	public function __construct($thumbnailPath, $size = self::SIZE_SMALL)
	{
		parent::__construct($thumbnailPath);

		$this->_size = $size;
		$this->_cache = new ImageCache();
	}
	
	/**
	 * Returns the absolute path to a cached thumbnail or false if no cached 
	 * copy exists
	 * @return string|false
	 */
	public function getPath()
	{
		$filename = $this->getFilename();

		if ($this->_cache->has($filename))
			return $this->_cache->getCachePath().DIRECTORY_SEPARATOR.$filename;

		return false;
	}

	/**
	 * Returns the URL to the thumbnail
	 * @return string
	 */
	public function getUrl()
	{
		$filename = $this->getFilename();

		if ($this->_cache->has($filename))
			return $this->_cache->getCacheUrl().'/'.$filename;

		// The image didn't exist in the cache
		return Yii::app()->controller->createUrl('thumbnail/generate', array(
					'path' => $this->_path,
					'size' => $this->_size));
	}
	
	/**
	 * @return string the URL to the original version of the image
	 */
	private function getOriginalUrl()
	{
		$response = Yii::app()->xbmc->performRequest('Files.PrepareDownload', array('path' => $this->_path));

		return Yii::app()->xbmc->getVFSHelper()->getUrl($response->result->details->path);
	}
	
	/**
	 * Generates and stores a cached copy of this thumbnail. It first retrieves 
	 * the original image to a temporary location for processing. This is done 
	 * because Kodi doesn't like the HTTP/1.0 requests that the built-in PHP 
	 * image manipulation functions (as well as file_get_contents()) use.
	 */
	public function generate()
	{
		// Create a HTTP/1.1 stream context
		$context = stream_context_create(array(
			'http' => array(
				'timeout' => Setting::getInteger('requestTimeout'),
				'protocol_version' => 1.1,
				'header' => 'Connection: close')));

		// Retrieve the image data and store it in a temporary file
		$imageData = file_get_contents($this->getOriginalUrl(), false, $context);
		$imageFile = tempnam(self::getTemporaryDirectory(), self::TEMP_FILE_PREFIX);
		
		if ($imageData === false || file_put_contents($imageFile, $imageData) === false)
			return;
		
		// Resize and cache the thumbnail
		$imagine = self::imagineFactory();

		$imagine->open($imageFile)
				->thumbnail(new \Imagine\Image\Box($this->_size, PHP_INT_MAX))
				->save($this->_cache->getCachePath().DIRECTORY_SEPARATOR.
						$this->getFilename(), array('jpeg_quality' => 70));

		// Delete the temporary file
		unlink($imageFile);
	}

	/**
	 * Determines the filename to be used for the thumbnail
	 * @return string
	 */
	private function getFilename()
	{
		return md5($this->_path).'_'.$this->_size.'.jpg';
	}
	
	/**
	 * Returns the temporary directory where images should be stored for 
	 * processing. It will first try upload_tmp_dir and fall back to the 
	 * system default if that directive has not been set.
	 * @return string
	 */
	private static function getTemporaryDirectory()
	{
		$tempDir = ini_get('upload_tmp_dir');

		if ($tempDir === null)
			$tempDir = sys_get_temp_dir();

		return $tempDir;
	}

	/**
	 * Factory method for creating an Imagine instance
	 * @return \Imagine\Gd\Imagine|\Imagine\Imagick\Imagine
	 */
	private static function imagineFactory()
	{
		// Try Imagick first, then fall back to GD. We have to check for
		// Imagick ourselves because Imagine is too stupid to do it correctly
		if (class_exists('Imagick', false))
			return new Imagine\Imagick\Imagine();
		else
			return new \Imagine\Gd\Imagine();
	}

}
