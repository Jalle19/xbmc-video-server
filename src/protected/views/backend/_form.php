<?php

/* @var $this BackendController */
/* @var $form TbActiveForm */
/* @var $model Backend */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL));

echo $form->textFieldControlGroup($model, 'name');
echo $form->checkBoxControlGroup($model, 'default');

echo '<hr />';

echo $form->textFieldControlGroup($model, 'hostname');
echo $form->textFieldControlGroup($model, 'port', array('span'=>1));
echo $form->textFieldControlGroup($model, 'username');
echo $form->passwordFieldControlGroup($model, 'password');

echo '<hr />';

$readmeUrl = 'https://github.com/Jalle19/xbmc-video-server#proxy-location';
echo $form->textFieldControlGroup($model, 'proxyLocation', array(
	'help'=>Yii::t('Backend', 'See {readmeUrl} for how to configure this', 
			array('{readmeUrl}'=>CHtml::link($readmeUrl, $readmeUrl)))));

echo $form->textFieldControlGroup($model, 'macAddress', array(
	'help'=>Yii::t('Backend', 'If a MAC address is entered a Wake-on-LAN packet will be sent to it whenever someone logs in')));

?>
<div class="form-actions">
	<?php echo TbHtml::submitButton($model->isNewRecord ? Yii::t('Forms', 'Create') : Yii::t('Forms', 'Save changes'), array(
		'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
	)); ?>
	<?php echo FormHelper::cancelButton(array('backend/admin')); ?>
</div>
<?php

$this->endWidget();