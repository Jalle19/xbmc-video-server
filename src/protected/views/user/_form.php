<?php

/* @var $this UserController */
/* @var $model User */
/* @var $form TbActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL)); 

echo $form->dropDownListControlGroup($model, 'role', $model->getRoles());
echo $form->textFieldControlGroup($model, 'username');
echo $form->passwordFieldControlGroup($model, 'password');

?>
<div class="form-actions">
	<?php echo TbHtml::submitButton($model->isNewRecord ? Yii::t('Forms', 'Create') : Yii::t('Forms', 'Update'), 
			array('color'=>TbHtml::BUTTON_COLOR_PRIMARY)); ?>
	<?php echo FormHelper::cancelButton(array('user/admin')); ?>
</div>

<?php $this->endWidget(); ?>