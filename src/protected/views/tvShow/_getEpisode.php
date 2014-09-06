<?php

/* @var $episode Episode */

?>
<p>
	<?php echo $episode->getEpisodeString().$episode->getWatchedIcon(); ?>
</p>

<?php 

$this->widget('WatchTVShowModal', array(
	'media'=>$episode))->renderTriggerButton();