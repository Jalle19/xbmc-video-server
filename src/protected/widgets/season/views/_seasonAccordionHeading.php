<?php

/* @var $season Season */
$artwork = ThumbnailFactory::create($season->getArtwork(),
		Thumbnail::SIZE_VERY_SMALL, ThumbnailFactory::THUMBNAIL_TYPE_SEASON);

?>
<div class="season row-fluid">
	
	<div class="pull-left season-artwork hide-when-toggled">
	<?php echo \yiilazyimage\components\LazyImage::image($artwork->getUrl()); ?>
	</div>

	<div class="pull-left">
		<?php echo CHtml::link($season->label, $linkUrl, $linkOptions); ?>

		<span class="season-info hide-when-toggled">
			<?php echo $season->getEpisodesString().$season->getWatchedIcon(); ?>
		</span>
	</div>
	
</div>