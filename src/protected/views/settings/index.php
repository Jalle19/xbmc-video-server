<?php

/* @var $form TbActiveForm */
/* @var $model ConfigForm */
$this->pageTitle = 'Settings';

?>
<h2>Settings</h2>

<hr />

<?php 

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
));

echo $form->textFieldControlGroup($model, 'hostname');
echo $form->textFieldControlGroup($model, 'port', array('span'=>1));
echo $form->textFieldControlGroup($model, 'username');
echo $form->passwordFieldControlGroup($model, 'password');

?>

<div class="form-actions">
	<?php echo TbHtml::submitButton('Save changes', array(
		'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
	)); ?>
</div>

<?php

$this->endWidget();