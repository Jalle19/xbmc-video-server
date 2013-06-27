<?php

/* @var $this MovieController */

$movieUrl = $this->createUrl('details', array('id'=>$data->movieid));
$thumbnailUrl = $this->createUrl('thumbnail/get', 
		array('path'=>$data->thumbnail, 'size'=>Thumbnail::THUMBNAIL_SIZE_MEDIUM, 'type'=>Thumbnail::TYPE_MOVIE));

$this->renderPartial('//videoLibrary/_gridItem', array(
	'label'=>$data->label,
	'itemUrl'=>$movieUrl,
	'thumbnailUrl'=>$thumbnailUrl,
));