<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::app()->name.' - '.Yii::t('Misc', 'Error');

?>

<div class="error-container">
	<h3><?php echo Yii::t('Misc', 'Error {code}', array('{code}'=>$code)); ?></h3>

	<div class="alert alert-block alert-error">
		<?php echo CHtml::encode($message); ?>
	</div>
</div>