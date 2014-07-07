<?php

/* @var $form TbActiveForm */
/* @var $model LoginForm */
$this->pageTitle = Yii::t('Login', 'Log in');

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL));

echo CHtml::tag('h2', array(), Yii::t('Login', 'Please log in'));
echo '<hr />';

echo $form->textFieldControlGroup($model, 'username', array('autofocus'=>'autofocus'));
echo $form->passwordFieldControlGroup($model, 'password');
echo $form->checkBoxControlGroup($model, 'rememberMe');

?>
<div class="form-actions">
	<?php echo TbHtml::submitButton(Yii::t('Login', 'Log in'), array(
		'color'=>TbHtml::BUTTON_COLOR_PRIMARY)); ?>
</div>

<?php 

$this->endWidget();