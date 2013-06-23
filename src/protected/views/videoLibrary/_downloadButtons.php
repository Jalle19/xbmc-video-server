<?php

$single = count($movieFiles) == 1;

if ($single)
{
	echo TbHtml::linkButton('Download', array(
		'color'=>TbHtml::BUTTON_COLOR_SUCCESS,
		'size'=>TbHtml::BUTTON_SIZE_LARGE,
		'url'=>$movieFiles[0],
	));
}
else
{
	foreach ($movieFiles as $k=> $movieFile)
	{
		echo CHtml::openTag('p');
		echo TbHtml::linkButton('Download (#'.++$k.')', array(
			'color'=>TbHtml::BUTTON_COLOR_SUCCESS,
			'url'=>$movieFile,
		));
		echo CHtml::closeTag('p');
	}
}