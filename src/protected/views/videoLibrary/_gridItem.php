<li class="span2">
	<a class="thumbnail" href="<?php echo $itemUrl; ?>">
		
		<div class="image-container">
			<?php echo Thumbnail::lazyImage($thumbnail); ?>
		</div>
		
		<div class="caption">
			<?php echo $label; ?>
		</div>
	</a>
</li>