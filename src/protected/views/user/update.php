<?php

/* @var $this UserController */
/* @var $model User */

$this->pageTitle = 'Update '.$model->username;

?>

<h2>Update <em><?php echo $model->username; ?></em></h2>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>