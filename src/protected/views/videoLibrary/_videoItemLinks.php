<?php

$numLinks = count($links);
$linkOptions = array('class'=>'fontastic-icon-disc');

foreach ($links as $k=> $link)
{
	if ($numLinks == 1)
		$label = 'Download';
	else
		$label = 'Download (part #'.(++$k).')';

	echo CHtml::tag('p', array(), CHtml::link($label, $link, $linkOptions));
}