<?php

/* @var $episode Episode */

?>
<p>
	<?php echo $episode->getEpisodeString().$episode->getWatchedIcon(); ?>
</p>

<?php 

// Omit URL credentials if browser is Internet Explorer
$this->widget('RetrieveTVShowWidget', array(
	'links'=>VideoLibrary::getVideoLinks($episode->file, Browser::isInternetExplorer()),
	'details'=>$episode,
));
