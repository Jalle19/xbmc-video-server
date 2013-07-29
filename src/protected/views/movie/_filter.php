<?php

/* @var $model MovieFilterForm */
/* @var $form FilterActiveForm */
$form = $this->beginWidget('FilterActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_INLINE,
	'method'=>'get'));

?>

<div class="movie-filter-form well">
	<?php

	// Cache this fragment if the "cache API calls" setting is enabled
	if (Setting::getValue('cacheApiCalls'))
	{
		if ($this->beginCache('MovieFilterTypeahead', array(
			'duration'=>31536000,
		)))
		{
			echo $form->typeaheadFieldControlGroup($model, 'name', 
					CJavaScript::encode($this->getMovieNames()), array(
						'id'=>'movie-filter-form-typeahead'
					));
			
			$this->endCache();
		}
	}
	else
		echo $form->typeaheadFieldControlGroup($model, 'name', CJavaScript::encode($this->getMovieNames()));
	
	echo $form->dropDownListControlGroup($model, 'genre', $model->getGenres(), array('prompt'=>''));
	echo $form->textFieldControlGroup($model, 'year', array('span'=>1));
	echo $form->dropDownListControlGroup($model, 'quality', $model->getQualities(), 
			array('prompt'=>'', 'style'=>'width: 70px;'));

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