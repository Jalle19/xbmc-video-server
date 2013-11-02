<?php

if (!isset($labelUrl))
	$labelUrl = $itemUrl;

?>
<li class="span2">
	<div class="thumbnail">
		
		<div class="image-container">
			<?php 
			
			if ($itemUrl)
				echo CHtml::link(Thumbnail::lazyImage($thumbnail), $itemUrl);
			else
				echo Thumbnail::lazyImage($thumbnail);
			
			?>
		</div>
		
		<div class="caption">
			<?php 
			
			if ($labelUrl)
				echo CHtml::link($label, $labelUrl);
			else
				echo $label;
			
			?>
		</div>
	
	</div>
</li>