<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = 'Create new user';
?>

<h2>Create new user</h2>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>