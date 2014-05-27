<?php

/* @var $season Season */

?>
<div class="season row-fluid">
	
	<div class="pull-left season-artwork">
	<?php echo Thumbnail::lazyImage(new ThumbnailSeason($season->getArtwork(), Thumbnail::SIZE_VERY_SMALL)); ?>
	</div>

	<div class="pull-left">
		<?php echo CHtml::link($season->getDisplayName(), $linkUrl, $linkOptions); ?>

		<span class="season-info">
			<?php echo $season->getEpisodesString(); ?>
		</span>
	</div>
	
</div>