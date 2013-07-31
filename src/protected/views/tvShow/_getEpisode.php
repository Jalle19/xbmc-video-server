<p>
	<?php echo VideoLibrary::getEpisodeString($episode->season, $episode->episode); ?>
</p>

<?php 

$this->widget('RetrieveTVShowWidget', array(
	'links'=>VideoLibrary::getVideoLinks($episode->file),
	'details'=>$episode,
));
