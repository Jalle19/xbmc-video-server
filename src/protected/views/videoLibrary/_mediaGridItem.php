<?php

/* @var $this MediaController */
/* @var $data Media */

$itemUrl = $this->createUrl('details', array('id'=>$data->getId()));
$thumbnail = ThumbnailFactory::create($data->getArtwork(), Thumbnail::SIZE_MEDIUM);

$this->renderPartial('//videoLibrary/_gridItem', array(
	'label'=>CHtml::link($data->label, $itemUrl),
	'itemUrl'=>$itemUrl,
	'thumbnail'=>$thumbnail,
	'watchedIcon'=>$data->getWatchedIcon(),
));