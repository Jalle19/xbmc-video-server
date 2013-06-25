<?php

/**
 * Represents a thumbnail. Using the get() function a resized version of the 
 * thumbnail is returned (according to the specified size). The resized image 
 * is automatically cached to the harddrive using ImageCache.
 *
 * @see ImageCache
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class Thumbnail
{

	const THUMBNAIL_SIZE_SMALL = 116;
	const THUMBNAIL_SIZE_MEDIUM = 160;
	const THUMBNAIL_SIZE_LARGE = 270;

	/**
	 * Returns the URL to the specified thumbnail, resized according to the 
	 * specified size
	 * @param string $thumbnailPath the thumbnail path
	 * @param int $size the size constant
	 * @return string
	 */
	public static function get($thumbnailPath, $size)
	{
		// TODO: Use better place holder image
		if (empty($thumbnailPath))
			return Yii::app()->baseUrl.'/images/blank.png';

		$filename = self::getFilename($thumbnailPath, $size);
		$cache = new ImageCache();

		// Put the resized version in the cache if it's not there yet
		if (!$cache->has($filename))
		{
			$response = Yii::app()->xbmc->performRequest('Files.PrepareDownload', array(
				'path'=>$thumbnailPath));

			$imageUrl = Yii::app()->xbmc->getAbsoluteVfsUrl($response->result->details->path);

			$image = new Eventviva\ImageResize($imageUrl);
			$image->resizeToWidth($size);
			$image->save($cache->getCachePath().DIRECTORY_SEPARATOR.$filename, IMAGETYPE_JPEG);
		}

		return $cache->getCacheUrl().'/'.$filename;
	}

	/**
	 * Determines the filename to be used for the thumbnail
	 * @param string $thumbnailPath the thumbnail path
	 * @param string $size the size constant
	 * @return string
	 */
	private static function getFilename($thumbnailPath, $size)
	{
		return md5($thumbnailPath).'_'.$size.'.jpg';
	}

}