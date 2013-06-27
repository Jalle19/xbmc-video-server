<?php

/**
 * Represents an actor thumbnail
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class ThumbnailActor extends Thumbnail
{

	protected function getPlaceholder()
	{
		return Yii::app()->baseUrl.'/images/placeholder-actor.jpg';
	}

}