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
echo $form->textFieldControlGroup($model, 'proxyLocation');

?>
<div class="form-actions">
	<?php echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save changes', array(
		'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
	)); ?>
	<?php echo FormHelper::cancelButton(array('backend/admin')); ?>
</div>
<?php

$this->endWidget();