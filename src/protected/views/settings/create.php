<?php

/* @var $this SettingsController */
/* @var $form TbActiveForm */
/* @var $model Backend */
$this->pageTitle = 'Add new backend';

?>
<h2>Create new backend</h2>

<hr />

<?php $this->renderPartial('_form', array('model'=>$model));