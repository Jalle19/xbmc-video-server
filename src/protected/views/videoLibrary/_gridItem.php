<li class="span2">
	<a class="thumbnail" href="<?php echo $itemUrl; ?>">
		<?php
		
		if($thumbnailUrl !== false)
		{
			?>
			<div class="image-container">
				<img src="<?php echo Yii::app()->baseUrl.'/images/blank.png'; ?>" 
					 data-src="<?php echo $thumbnailUrl; ?>" alt="" 
					 class="lazy" />
			</div>
			<?php
		}
			
		
		?>
		<div class="caption">
			<?php echo $label; ?>
		</div>
	</a>
</li>