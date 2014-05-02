<?php

/* @var $this Controller */
/* @var $model ChangeLanguageForm */
/* @var $form TbActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL)); 

echo $form->dropdownListControlGroup($model, 'language', Yii::app()->languageManager->getAvailableLanguages());
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