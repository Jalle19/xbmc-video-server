<?php

// If $itemUrl is false we use a <div> instead of an <a>
if ($itemUrl !== false)
	$thumbnailOpen = CHtml::openTag('a', array('class'=>'thumbnail', 'href'=>$itemUrl));
else
	$thumbnailOpen = CHtml::openTag('div', array('class'=>'thumbnail'));

$thumbnailClose = CHtml::closeTag($itemUrl !== false ? 'a' : 'div');

?>
<li class="span2">
	<?php echo $thumbnailOpen; ?>
		
		<div class="image-container">
			<?php echo Thumbnail::lazyImage($thumbnail); ?>
		</div>
		
		<div class="caption">
			<?php echo $label; ?>
		</div>
	
	<?php echo $thumbnailClose; ?>
</li>