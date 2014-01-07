<?php

/* @var $this MovieController */

$movieUrl = $this->createUrl('details', array('id'=>$data->movieid));

$thumbnail = new ThumbnailVideo($data->thumbnail, Thumbnail::SIZE_MEDIUM);

$this->renderPartial('//videoLibrary/_gridItem', array(
	'label'=>CHtml::link($data->label, $movieUrl),
	'itemUrl'=>$movieUrl,
	'thumbnail'=>$thumbnail,
));