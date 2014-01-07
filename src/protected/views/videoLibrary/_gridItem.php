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
			<?php echo $label; ?>
		</div>
	
	</div>
</li>