<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = 'Create New User';
?>

<h2>Create New User</h2>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>