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
	 * @var string the name of the image file
	 */
	private $_filename;
	
	/**
	 * @var ImageCache the cache
	 */
	private $_cache;
	
	/**
	 * Returns the place holder image (used when no thumbnail exists)
	 * @return string
	 */
	protected function getPlaceholder()
	{
		return Yii::app()->baseUrl.'/images/blank.png';
	}
	
	/**
	 * Class constructor
	 * @param string $thumbnailPath the thumbnail path
	 * @param int $size the size constant
	 */
	public function __construct($thumbnailPath, $size)
	{
		if (empty($thumbnailPath))
		{
			$this->_filename = $this->getPlaceholder();
			return;
		}
		else
			$this->_filename = $this->getFilename($thumbnailPath, $size);
		
		$this->_cache = new ImageCache();

		// Put the resized version in the cache if it's not there yet
		if (!$this->_cache->has($this->_filename))
		{
			$response = Yii::app()->xbmc->performRequest('Files.PrepareDownload', array(
				'path'=>$thumbnailPath));

			$imageUrl = Yii::app()->xbmc->getAbsoluteVfsUrl($response->result->details->path);

			$image = new Eventviva\ImageResize($imageUrl);
			$image->resizeToWidth($size);
			$image->save($this->_cache->getCachePath().DIRECTORY_SEPARATOR.$this->_filename, IMAGETYPE_JPEG);
		}
	}

	/**
	 * Determines the filename to be used for the thumbnail
	 * @param string $thumbnailPath the thumbnail path
	 * @param string $size the size constant
	 * @return string
	 */
	private function getFilename($thumbnailPath, $size)
	{
		return md5($thumbnailPath).'_'.$size.'.jpg';
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

		$cs->registerScriptFile(Yii::app()->baseUrl
				.'/js/jquery.unveil.js', CClientScript::POS_END);

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
	 * Returns the URL to the thumbnail
	 * @return string
	 */
	public function __toString()
	{
		if ($this->_filename === $this->getPlaceholder())
			return $this->_filename;
		else
			return $this->_cache->getCacheUrl().'/'.$this->_filename;
	}

}