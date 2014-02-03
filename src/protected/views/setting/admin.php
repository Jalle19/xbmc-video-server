<?php

/* @var $this SettingsController */
/* @var $form TbActiveForm */
/* @var $settings Setting[] */
$this->pageTitle = 'Settings';

?>
<h2>Settings</h2>

<?php echo FormHelper::helpBlock('This is where you configure global 
	application settings. These settings apply regardless of which backend is 
	currently in use.'); ?>

<hr />

<?php

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL));

foreach ($settings as $setting)
{
	$name = $setting->name;
	
	if (isset(Setting::$definitions[$name]['htmlOptions']))
		$htmlOptions = Setting::$definitions[$name]['htmlOptions'];
	else
		$htmlOptions = array();

	if (isset(Setting::$definitions[$name]['description']))
		$htmlOptions['help'] = Setting::$definitions[$name]['description'];
	
	switch (Setting::$definitions[$name]['type'])
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
		default:
			break;
	}
}

echo CHtml::openTag('div', array('class'=>'form-actions'));
echo TbHtml::submitButton('Save changes', array(
	'color'=>TbHtml::BUTTON_COLOR_PRIMARY));
echo TbHtml::linkButton('Reset to defaults', array(
	'url'=>array('setting/reset'),
	'color'=>TbHtml::BUTTON_COLOR_INFO, 
	'class'=>'btn-padded'));
echo CHtml::closeTag('div');

$this->endWidget();