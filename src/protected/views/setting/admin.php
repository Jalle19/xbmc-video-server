<?php

/* @var $this SettingsController */
/* @var $form TbActiveForm */
/* @var $settings Setting[] */
/* @var $definitions array */
$this->pageTitle = $title = Yii::t('Settings', 'Settings');

?>
<h2><?php echo $title; ?></h2>

<?php echo FormHelper::helpBlock(Yii::t('Settings', 'This is where you configure global 
	application settings. These settings apply regardless of which backend is 
	currently in use.')); ?>

<hr />

<?php

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL));

foreach ($settings as $setting)
{
	$name = $setting->name;
	
	if (isset($definitions[$name]['htmlOptions']))
		$htmlOptions = $definitions[$name]['htmlOptions'];
	else
		$htmlOptions = array();

	if (isset($definitions[$name]['description']))
		$htmlOptions['help'] = $definitions[$name]['description'];
	
	switch ($definitions[$name]['type'])
	{
		case Setting::TYPE_TEXT_WIDE:
			$htmlOptions['class'] = 'span5';
			// fall through
		case Setting::TYPE_TEXT:
			echo $form->textFieldControlGroup($setting, $name, $htmlOptions);
			break;
		case Setting::TYPE_CHECKBOX:
			echo $form->checkBoxControlGroup($setting, $name, $htmlOptions);
			break;
		case Setting::TYPE_DROPDOWN:
			echo $form->dropDownListControlGroup($setting, $name, $definitions[$name]['listData'], $htmlOptions);
			break;
		default:
			break;
	}
}

echo CHtml::openTag('div', array('class'=>'form-actions'));
echo TbHtml::submitButton(Yii::t('Forms', 'Save changes'), array(
	'color'=>TbHtml::BUTTON_COLOR_PRIMARY));
echo TbHtml::linkButton(Yii::t('Forms', 'Reset to defaults'), array(
	'url'=>array('setting/reset'),
	'color'=>TbHtml::BUTTON_COLOR_INFO, 
	'class'=>'btn-padded'));
echo CHtml::closeTag('div');

$this->endWidget();