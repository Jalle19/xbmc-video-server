<p>
	<?php echo VideoLibrary::getEpisodeString($episode->season, $episode->episode); ?>
</p>

<?php 

// Omit URL credentials if browser is Internet Explorer
$this->widget('RetrieveTVShowWidget', array(
	'links'=>VideoLibrary::getVideoLinks($episode->file, Browser::isInternetExplorer()),
	'details'=>$episode,
));
