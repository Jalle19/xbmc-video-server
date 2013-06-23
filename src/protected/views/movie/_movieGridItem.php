<?php

/* @var $this MovieController */
$movieUrl = $this->createUrl('details', array('id'=>$data->movieid));
$thumbnailUrl = $this->createUrl('thumbnail/get', 
		array('thumbnailPath'=>$data->thumbnail));

?>
<li class="span2">
	<a class="thumbnail" href="<?php echo $movieUrl; ?>">
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
			<?php echo $data->label; ?>
		</div>
	</a>
</li>