<li class="span2">
	<a class="thumbnail" href="<?php echo $itemUrl; ?>">
		<?php
		
		if($thumbnailUrl !== false)
		{
			?>
			<div class="image-container">
				<?php echo Thumbnail::lazyImage($thumbnailUrl); ?>
			</div>
			<?php
		}
			
		
		?>
		<div class="caption">
			<?php echo $label; ?>
		</div>
	</a>
</li>