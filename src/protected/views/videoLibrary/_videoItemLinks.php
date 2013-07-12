<div class="item-links">
	<?php

	$numLinks = count($links);
	$linkOptions = array('class'=>'fontastic-icon-disc');

	foreach ($links as $k=> $link)
	{
		// false means the file doesn't exist on disk anymore
		if ($link === false)
		{
			echo CHtml::tag('p', array('class'=>'missing-video-file'), 
					TbHtml::icon (TBHtml::ICON_WARNING_SIGN).'The file(s) for this item is not available');
		}
		else
		{
			if ($numLinks == 1)
				$label = 'Download';
			else
				$label = 'Download (part #'.(++$k).')';

			echo CHtml::tag('p', array(), CHtml::link($label, $link, $linkOptions));
		}
	}

	?>
</div>