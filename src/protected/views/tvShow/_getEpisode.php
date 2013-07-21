<p>
	<?php echo VideoLibrary::getEpisodeString($episode->season, $episode->episode); ?>
</p>

<?php 

$links = VideoLibrary::getVideoLinks($episode->file);

if (count($links) === 1 && Setting::getValue('singleFilePlaylist'))
	$watchUrl = $links[0];
else
	$watchUrl = array('getEpisodePlaylist', 'episodeId'=>$episode->episodeid);

echo TbHtml::linkButton('Watch', array(
	'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
	'size'=>TbHtml::BUTTON_SIZE_SMALL,
	'url'=>$watchUrl,
	'class'=>'fontastic-icon-play',
)); 

$this->renderPartial('/videoLibrary/_videoItemLinks', array(
	'links'=>$links));
