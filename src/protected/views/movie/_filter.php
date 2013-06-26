<?php

/* @var $model MovieFilterForm */
/* @var $form TbActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_INLINE,
	'method'=>'get',
)); 

// Enable typeahead on the name field
$typeaheadData = CJavaScript::encode($this->getMovieNames());

Yii::app()->clientScript->registerScript(__CLASS__.'_nameTypeahead', "
	$('#movie-filter-name').typeahead({
		name: 'movie-name',
		local: {$typeaheadData},
		limit: 10
	});
", CClientScript::POS_READY);

?>

<div class="movie-filter-form well">
	<?php

	echo $form->textFieldControlGroup($model, 'name', array(
		'id'=>'movie-filter-name',
		'class'=>'twitter-typeahead-input'));

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