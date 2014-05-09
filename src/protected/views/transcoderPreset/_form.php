<?php
/* @var $this TranscoderPresetController */
/* @var $model TranscoderPreset */
/* @var $form TbActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL));

echo '<hr />';

echo $form->textFieldControlGroup($model, 'name');
echo $form->dropDownListControlGroup($model, 'video_codec', TranscoderPreset::$validVideoCodecs);
echo $form->textFieldControlGroup($model, 'video_bitrate', array('span'=>1));
echo $form->dropDownListControlGroup($model, 'resolution', TranscoderPreset::$validResolutions);
echo $form->dropDownListControlGroup($model, 'audio_codec', TranscoderPreset::$validAudioCodecs);
echo $form->textFieldControlGroup($model, 'audio_bitrate');
echo $form->dropDownListControlGroup($model, 'audio_channels', TranscoderPreset::$validAudioChannels);

?>
<div class="form-actions">
	<?php echo TbHtml::submitButton($model->isNewRecord ? 'Create' : 'Save changes', array(
		'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
	)); ?>
	<?php echo FormHelper::cancelButton(array('transcoderPreset/admin')); ?>
</div>

<?php

$this->endWidget();

