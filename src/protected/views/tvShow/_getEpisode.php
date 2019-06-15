<?php

/* @var $episode Episode */

?>
<p class="episode-list-string">
	<?php echo $episode->getEpisodeString().$episode->getWatchedIcon(); ?>
</p>

<?php 

$this->widget('WatchTVShowModal', array(
	'media'=>$episode))->renderTriggerButton();
