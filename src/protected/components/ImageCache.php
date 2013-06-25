<?php

/**
 * Simple image caching component.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class ImageCache
{

	/**
	 * @var string the absolute path to the cache
	 */
	private $_cachePath;

	/**
	 * @var string the URL to the cache
	 */
	private $_cacheUrl;

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		$this->_cachePath = realpath(Yii::app()->basePath.'/..').'/images/image-cache';
		$this->_cacheUrl = Yii::app()->baseUrl.'/images/image-cache';
	}

	/**
	 * Checks if the given file exists in the cache
	 * @param string $filename
	 * @return boolean
	 */
	public function has($filename)
	{
		return file_exists($this->_cachePath.DIRECTORY_SEPARATOR.$filename);
	}

	/**
	 * Returns the URL to the given file (assuming it is cached)
	 * @param string $filename
	 * @return string
	 */
	public function get($filename)
	{
		return $this->_cacheUrl.'/'.$filename;
	}

	/**
	 * Getter for _cachePath
	 * @return string
	 */
	public function getCachePath()
	{
		return $this->_cachePath;
	}

	/**
	 * Getter for _cacheUrl
	 * @return string
	 */
	public function getCacheUrl()
	{
		return $this->_cacheUrl;
	}

}