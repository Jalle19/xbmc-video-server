<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = 'Change password';

?>

<h2>Change password</h2>

<hr />

<?php echo $this->renderPartial('_changePasswordForm', array('model'=>$model)); ?>
