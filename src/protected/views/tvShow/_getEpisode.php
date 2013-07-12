<p>
	<?php echo VideoLibrary::getEpisodeString($episode->season, $episode->episode); ?>
</p>

<?php 

echo TbHtml::linkButton('Watch', array(
	'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
	'size'=>TbHtml::BUTTON_SIZE_SMALL,
	'url'=>$this->createUrl('getEpisodePlaylist', array(
		'episodeId'=>$episode->episodeid)),
	'class'=>'fontastic-icon-play',
)); 

$this->renderPartial('/videoLibrary/_videoItemLinks', array(
	'links'=>VideoLibrary::getVideoLinks($episode->file)));
