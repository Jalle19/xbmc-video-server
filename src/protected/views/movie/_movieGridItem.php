<?php

/* @var $this MovieController */

$movieUrl = $this->createUrl('details', array('id'=>$data->movieid));

$thumbnail = new ThumbnailVideo($data->thumbnail, Thumbnail::SIZE_MEDIUM);

$this->renderPartial('//videoLibrary/_gridItem', array(
	'label'=>$data->label,
	'itemUrl'=>$movieUrl,
	'thumbnail'=>$thumbnail,
));