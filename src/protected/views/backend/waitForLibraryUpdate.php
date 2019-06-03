<?php

$this->pageTitle = $title = Yii::t('Backend', 'Waiting for library update');

// Wait for the library update to complete before redirecting the user to the 
// previous location
Yii::app()->clientScript->registerScript(__CLASS__.'_waitForLibraryUpdate', '
	waitForLibraryUpdate("'.$previousLocation.'");
', CClientScript::POS_END);

?>
<div class="center-wrapper">
	<img class="large-loader" src="<?php echo Yii::app()->baseUrl.'/images/loader-large.gif'; ?>" />

	<p>
		<p><?php echo Yii::t('Backend', 'Library update triggered, this page will be automatically refreshed once the update has completed'); ?></p>
		<p><?php echo Yii::t('Backend', "You can leave this page at any time, the library update will finish in the background"); ?></p>
	</p>
</div>
