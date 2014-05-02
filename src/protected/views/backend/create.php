<?php

/* @var $this BackendController */
/* @var $form TbActiveForm */
/* @var $model Backend */
$this->pageTitle = $title = Yii::t('Backend', 'Create new backend');

?>
<h2><?php echo $title; ?></h2>

<hr />

<?php $this->renderPartial('_form', array('model'=>$model));