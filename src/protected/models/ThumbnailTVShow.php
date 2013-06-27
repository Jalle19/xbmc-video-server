<?php

/**
 * Represents a TV show thumbnail
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class ThumbnailTVShow extends Thumbnail
{

	protected function getPlaceholder()
	{
		return Yii::app()->baseUrl.'/images/placeholder-video.jpg';
	}

}