<?php

/* @var $this UserController */
/* @var $model User */
/* @var $form TbActiveForm */

// Determine cancel action
$cancelAction = $model->getStartPageRoute();

if (Yii::app()->user->getRole() === User::ROLE_ADMIN) {
	$cancelAction = array('user/admin');
}

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL)); 

if (Yii::app()->user->getRole() === User::ROLE_ADMIN) {
	echo $form->dropDownListControlGroup($model, 'role', $model->getRoles());

	echo '<hr />';
	
	echo $form->textFieldControlGroup($model, 'username');
	echo $form->passwordFieldControlGroup($model, 'password');

	echo '<hr />';
}

echo $form->dropDownListControlGroup($model, 'language', LanguageManager::getAvailableLanguages());
echo $form->dropDownListControlGroup($model, 'start_page', $model->getStartPages());
echo $form->hiddenField($model, 'id');

?>
<div class="form-actions">
	<?php
	
	echo TbHtml::submitButton($model->isNewRecord ? Yii::t('Forms', 'Create') : Yii::t('Forms', 'Update'), 
			array('color'=>TbHtml::BUTTON_COLOR_PRIMARY));
	
	// Don't show the change password button when an administrator is editing another user
	if (Yii::app()->user->getRole() !== User::ROLE_ADMIN || Yii::app()->user->id === $model->id)
	{
		echo TbHtml::linkButton(Yii::t('Menu', 'Change password'), array(
			'url'=>array('user/changePassword'),
			'class'=>'btn-padded',
			'color'=>TbHtml::BUTTON_COLOR_PRIMARY));
	}
	
	echo FormHelper::cancelButton($cancelAction);
	
	?>
</div>

<?php $this->endWidget(); ?>
