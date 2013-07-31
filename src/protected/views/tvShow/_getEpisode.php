<p>
	<?php echo VideoLibrary::getEpisodeString($episode->season, $episode->episode); ?>
</p>

<?php 

$this->widget('RetrieveMediaWidget', array(
	'type'=>RetrieveMediaWidget::MEDIA_TYPE_TVSHOW,
	'links'=>VideoLibrary::getVideoLinks($episode->file),
	'details'=>$episode,
));
