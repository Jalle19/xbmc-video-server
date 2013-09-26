<?php

/* @var $model MovieFilterForm */
/* @var $form FilterActiveForm */
$form = $this->beginWidget('FilterActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_INLINE,
	'method'=>'get'));

?>

<div class="movie-filter-form well">
	<?php

	// Cache the encoded JavaScript if the "cache API calls" setting is enabled
	if (Setting::getValue('cacheApiCalls'))
	{
		$cacheId = 'MovieFilterTypeahead';
		$typeaheadData = Yii::app()->apiCallCache->get($cacheId);

		if ($typeaheadData === false)
		{
			$typeaheadData = CJavaScript::encode($this->getMovieNames());
			Yii::app()->apiCallCache->set($cacheId, $typeaheadData);
		}
	}
	else
		$typeaheadData = CJavaScript::encode($this->getMovieNames());
	
	echo $form->typeaheadFieldControlGroup($model, 'name', $typeaheadData);
	echo $form->dropDownListControlGroup($model, 'genre', $model->getGenres(), array('prompt'=>''));
	echo $form->textFieldControlGroup($model, 'year', array('span'=>1));
	echo $form->dropDownListControlGroup($model, 'quality', $model->getQualities(), 
			array('prompt'=>'', 'style'=>'width: 70px;'));
	echo $form->dropDownListControlGroup($model, 'watchedStatus', VideoFilterForm::getWatchedStatuses(), array(
		'prompt'=>'', 'style'=>'width: 120px;'));

	?>
	<div class="buttons">
		<?php
		echo TbHtml::submitButton('Apply filter', array(
			'color'=>TbHtml::BUTTON_COLOR_PRIMARY));

		echo TbHtml::linkButton('Clear filter', array(
			'color'=>TbHtml::BUTTON_COLOR_INFO,
			'url'=>array('movie/index')));

		?>
	</div>
</div>
<?php

$this->endWidget();