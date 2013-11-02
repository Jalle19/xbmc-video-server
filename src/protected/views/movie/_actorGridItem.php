<?php

/* @var $this MovieController */

$itemUrl = $this->createUrl('movie/index', array(
	'MovieFilterForm[actor]'=>$data->name));

$this->renderPartial('//videoLibrary/_actorGridItem', array(
	'data'=>$data,
	'itemUrl'=>$itemUrl,
));
