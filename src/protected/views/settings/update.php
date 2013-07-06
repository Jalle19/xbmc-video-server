<?php

/* @var $this SettingsController */
/* @var $model Backend */

$this->pageTitle = 'Update '.$model->name;

?>

<h2>Update <em><?php echo $model->username; ?></em></h2>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model));