<?php

/* @var $this Controller */
/* @var $model ChangeLanguageForm */
/* @var $form TbActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL)); 

// Notify administrators that changing the language here only affects them
if (Yii::app()->user->getRole() === User::ROLE_ADMIN)
{
	$settingsLink = CHtml::link(Yii::t('Settings', 'Settings'), $this->createUrl('setting/admin'));

	echo FormHelper::helpBlock(Yii::t('Language', 'To change the application language for all users go to {settingsUrl} instead', array(
		'{settingsUrl}'=>$settingsLink)));
}

echo $form->dropdownListControlGroup($model, 'language', LanguageManager::getAvailableLanguages());
echo $form->checkboxControlGroup($model, 'setDefault');

?>
<div class="form-actions">
	<?php echo TbHtml::submitButton(Yii::t('ChangeLanguageForm', 'Change language'),
			array('color'=>TbHtml::BUTTON_COLOR_PRIMARY)); ?>
	
	<?php echo TbHtml::button(Yii::t('ChangeLanguageForm', 'Close'), array(
		'class'=>'btn-padded',
		'color'=>TbHtml::BUTTON_COLOR_INFO,
		'data-toggle'=>'modal',
		'data-target'=>'#change-language-modal',
	)); ?>
</div>

<?php $this->endWidget();