<?php

/* @var $this UserController */
/* @var $model ChangePasswordForm */

$this->pageTitle = $title = Yii::t('User', 'Change password');

?>

<h2><?php echo $title; ?></h2>

<hr />

<?php echo $this->renderPartial('_changePasswordForm', array('model'=>$model)); ?>
