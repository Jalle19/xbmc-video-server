<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::app()->name.' - Error';
$this->breadcrumbs = array(
	'Error',
);
?>

<h3>Error <?php echo $code; ?></h3>

<div class="alert alert-block alert-error">
	<?php echo CHtml::encode($message); ?>
</div>