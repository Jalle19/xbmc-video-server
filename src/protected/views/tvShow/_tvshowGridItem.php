<?php

/* @var $this TvShowController */
/* @var $data TVShow */

$showUrl = $this->createUrl('details', array('id'=>$data->getId()));
$thumbnail = new ThumbnailVideo($data->getArtwork(), Thumbnail::SIZE_MEDIUM);

$this->renderPartial('//videoLibrary/_gridItem', array(
	'label'=>CHtml::link($data->label, $showUrl),
	'itemUrl'=>$showUrl,
	'thumbnail'=>$thumbnail,
));