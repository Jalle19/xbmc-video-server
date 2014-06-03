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
class Thumbnail
{

	const SIZE_VERY_SMALL = 70;
	const SIZE_SMALL = 116;
	const SIZE_MEDIUM = 160;
	const SIZE_LARGE = 270;
	
	/**
	 * @var string the path to the thumbnail
	 */
	private $_path;
	
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
	 * @param string $thumbnailPath the thumbnail path. If empty the placeholder 
	 * image will be used instead.
	 * @param int $size the size constant
	 */
	public function __construct($thumbnailPath, $size)
	{
		if (empty($thumbnailPath))
			$thumbnailPath = $this->getPlaceholder();

		$this->_path = $thumbnailPath;
		$this->_size = $size;
		$this->_cache = new ImageCache();
	}
	
	/**
	 * Returns the URL to a cached thumbnail or false if no cached copy exists
	 * @return mixed
	 */
	public function getUrl()
	{
		$filename = $this->getFilename();

		if ($this->_cache->has($filename))
			return $this->_cache->getCacheUrl().'/'.$filename;

		return false;
	}
	
	/**
	 * Generates and stores a cached copy of this thumbnail
	 */
	public function generate()
	{
		// The path to the image can be either the placeholder image, in which 
		// case we just prepend the host info, or it can be a VFS url in which 
		// case we need to resolve it
		if (substr($this->_path, 0, 5) === 'image')
		{
			$response = Yii::app()->xbmc->performRequest('Files.PrepareDownload', 
					array('path'=>$this->_path));

			$imageUrl = Yii::app()->xbmc->getAbsoluteVfsUrl($response->result->details->path);
		}
		else
			$imageUrl = Yii::app()->request->getHostInfo().$this->_path;

		// Resize the image and save it in the cache
		$image = new Eventviva\ImageResize($imageUrl);
		$image->resizeToWidth($this->_size);
		$image->save($this->_cache->getCachePath().DIRECTORY_SEPARATOR.
				$this->getFilename(), IMAGETYPE_JPEG);
	}
	
	/**
	 * Returns the place holder image (used when no thumbnail exists)
	 * @return string
	 */
	protected function getPlaceholder()
	{
		return Yii::app()->baseUrl.'/images/blank.png';
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
	 * Converts this object to the URL to the thumbnail it represents. If a 
	 * cached copy exists, that URL will be returned, otherwise a URL which 
	 * generates the image is returned. This way the image will only be 
	 * generated once it is displayed.
	 * @return string the URL to the thumbnail
	 */
	public function __toString()
	{
		$url = $this->getUrl();

		if ($url === false)
		{
			$url = Yii::app()->controller->createUrl('thumbnail/generate', array(
				'path'=>$this->_path,
				'size'=>$this->_size));
		}

		return $url;
	}

}