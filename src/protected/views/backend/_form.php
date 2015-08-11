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

$readmeUrl = 'https://github.com/Jalle19/xbmc-video-server/wiki/Configuring-a-reverse-proxy';
echo $form->textFieldControlGroup($model, 'proxyLocation', array(
	'help'=>Yii::t('Backend', 'See {readmeUrl} for how to configure this', 
			array('{readmeUrl}'=>CHtml::link($readmeUrl, $readmeUrl, array('target'=>'_blank'))))));

echo '<hr />';

echo FormHelper::helpBlock(Yii::t('Backend', 'The following items are only required if you want the backend to me woken using Wake on LAN'));

echo $form->textFieldControlGroup($model, 'macAddress', array(
	'help'=>Yii::t('Backend', 'If a MAC address is entered a Wake-on-LAN packet will be sent to it whenever someone logs in')));

echo $form->textFieldControlGroup($model, 'subnetMask', array(
	'help'=>Yii::t('Backend', "If you don't know what this, leave it empty. Otherwise enter the subnet mask, e.g. 255.255.0.0")));

echo '<hr />';

echo FormHelper::helpBlock(Yii::t('Backend', "The following item must only be changed if you've changed the port through advancedsettings.xml"));

echo $form->textFieldControlGroup($model, 'tcp_port', array(
	'span'=>1,
	'help'=>Yii::t('Backend', 'If the backend allows TCP connections from other machines, XBMC Video Server will use it to determine when triggered library updates are finished')));

?>
<div class="form-actions">
	<?php echo TbHtml::submitButton($model->isNewRecord ? Yii::t('Forms', 'Create') : Yii::t('Forms', 'Save changes'), array(
		'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
	)); ?>
	<?php echo FormHelper::cancelButton(array('backend/admin')); ?>
</div>
<?php

$this->endWidget();