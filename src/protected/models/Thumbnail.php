<?php

/**
 * Represents a thumbnail. When the constructor is called a resized version of 
 * the thumbnail is created according to the specified size. The resized image 
 * is automatically cached to the harddrive using ImageCache.
 * 
 * When used as a string the object will return the URL to the requested 
 * thumbnail, or a place holder if no thumbnail exists.
 *
 * @see ImageCache
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class Thumbnail
{

	const SIZE_SMALL = 116;
	const SIZE_MEDIUM = 160;
	const SIZE_LARGE = 270;
	
	const TYPE_VIDEO = 'movie';
	const TYPE_ACTOR = 'actor';

	/**
	 * @var string the URL to the place holder image
	 */
	protected $_placeholder;

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
	 * @param string $thumbnailPath the thumbnail path
	 * @param int $size the size constant
	 */
	public function __construct($thumbnailPath, $size)
	{
		$this->_path = $thumbnailPath;
		$this->_size = $size;
		$this->_cache = new ImageCache();
	}
	
	/**
	 * Returns the URL to a cached thumbnail or false if no cached copy exists. 
	 * If the thumbnail path is empty, the placeholder image will be returned.
	 * @return mixed
	 */
	public function getUrl()
	{
		if (empty($this->_path))
			return $this->getPlaceholder();
		else
		{
			$filename = $this->getFilename();

			if ($this->_cache->has($filename))
				return $this->_cache->getCacheUrl().'/'.$filename;

			return false;
		}
	}
	
	/**
	 * Generates and stores a cached copy of this thumbnail
	 */
	public function generate()
	{
		$response = Yii::app()->xbmc->performRequest('Files.PrepareDownload', 
				array('path'=>$this->_path));

		$imageUrl = Yii::app()->xbmc->getAbsoluteVfsUrl($response->result->details->path);

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
	 * Returns the HTML for a lazy loaded image.
	 * @param string $url the image URL
	 * @param array $htmlOptions the htmlOptions for the image tag
	 * @return string
	 */
	public static function lazyImage($url, $htmlOptions = array())
	{
		// Register the lazy loader
		$cs = Yii::app()->clientScript;

		$script = YII_DEBUG ? 'jquery.unveil.js' : 'jquery.unveil.min.js';
		$cs->registerScriptFile(Yii::app()->baseUrl
				.'/js/jquery-unveil/'.$script, CClientScript::POS_END);

		$cs->registerScript(__CLASS__.'_unveil', '
			$(".lazy").unveil(50);
		', CClientScript::POS_READY);

		$htmlOptions = array_merge(
				TbHtml::addClassName('lazy', $htmlOptions), 
				array('data-src'=>$url));

		return CHtml::image(Yii::app()->baseUrl.'/images/loader.gif', '', 
				$htmlOptions);
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