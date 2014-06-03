<li class="span2">
	<div class="thumbnail">
		
		<div class="image-container">
			<?php 
			
			$image = \yiilazyimage\components\LazyImage::image($thumbnail);
			
			if ($itemUrl)
				echo CHtml::link($image, $itemUrl);
			else
				echo $image;
			
			?>
		</div>
		
		<div class="caption">
			<?php echo $label; ?>
		</div>
	
	</div>
</li>