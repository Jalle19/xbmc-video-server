<?php

/* @var $this UserController */
/* @var $model User */
/* @var $form TbActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL)); 

echo $form->passwordFieldControlGroup($model, 'password');

?>
<div class="form-actions">
	<?php echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Update',
			array('color'=>TbHtml::BUTTON_COLOR_PRIMARY)); ?>
	<?php echo FormHelper::cancelButton(array('movie/index')); ?>
</div>

<?php $this->endWidget(); ?>