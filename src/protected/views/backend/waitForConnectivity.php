<?php

$this->pageTitle = $title = Yii::t('Backend', 'Waiting for connection');

// Start the poller which redirects once the backend is reachable
Yii::app()->clientScript->registerScript(__CLASS__.'_pollForConnectivity', '
	pollForConnectivity();
', CClientScript::POS_END);

?>

<div class="center-wrapper">
	<img class="large-loader" src="<?php echo Yii::app()->baseUrl.'/images/loader-large.gif'; ?>" />

	<p>
		<p><?php echo Yii::t('Backend', 'Wake on LAN packet sent, this page will be automatically refreshed once the backend is reachable'); ?></p>
		<p><?php echo Yii::t('Backend', "If the backend has been powered off completely you'll have to turn it on manually."); ?></p>
	</p>
</div>