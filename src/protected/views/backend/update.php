<?php

/* @var $this BackendController */
/* @var $model Backend */

$this->pageTitle = $title = Yii::t('Backend', 'Update {backendName}', 
		array('{backendName}'=>$model->name));

?>

<h2><?php echo $title; ?></h2>

<hr />

<?php echo $this->renderPartial('_form', array('model'=>$model));