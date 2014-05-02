<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = $title = Yii::t('User', 'Create new user');
?>

<h2><?php echo $title; ?></h2>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>