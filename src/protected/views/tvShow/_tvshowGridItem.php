<?php

/* @var $this TvShowController */

$showUrl = $this->createUrl('details', array('id'=>$data->tvshowid));
$thumbnailUrl = $this->createUrl('thumbnail/get', 
		array('thumbnailPath'=>$data->thumbnail));

$this->renderPartial('//videoLibrary/_gridItem', array(
	'label'=>$data->label,
	'itemUrl'=>$showUrl,
	'thumbnailUrl'=>$thumbnailUrl,
));