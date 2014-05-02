<?php

/* @var $this UserController */
/* @var $model User */
/* @var $form TbActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL)); 

echo $form->passwordFieldControlGroup($model, 'currentPassword');
echo $form->passwordFieldControlGroup($model, 'newPassword');
echo $form->passwordFieldControlGroup($model, 'newPasswordRepeat');

?>
<div class="form-actions">
	<?php echo TbHtml::submitButton(Yii::t('User', 'Change password'),
			array('color'=>TbHtml::BUTTON_COLOR_PRIMARY)); ?>
	<?php echo FormHelper::cancelButton(array('movie/index')); ?>
</div>

<?php $this->endWidget(); ?>