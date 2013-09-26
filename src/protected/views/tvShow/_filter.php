<?php

/* @var $model TVShowFilterForm */
/* @var $form FilterActiveForm */

$form = $this->beginWidget('FilterActiveForm', array(
	'layout'=>TbHtml::FORM_LAYOUT_INLINE,
	'method'=>'get')); 

?>

<div class="movie-filter-form well">
	<?php

	echo $form->typeaheadFieldControlGroup($model, 'name', 
			CJavaScript::encode($this->getTVShowNames()));

	echo $form->dropDownListControlGroup($model, 'genre', $model->getGenres(), array('prompt'=>''));
	echo $form->dropDownListControlGroup($model, 'watchedStatus', VideoFilterForm::getWatchedStatuses(), array(
		'prompt'=>'', 'style'=>'width: 120px;'));

	?>
	<div class="buttons">
		<?php
		echo TbHtml::submitButton('Apply filter', array(
			'color'=>TbHtml::BUTTON_COLOR_PRIMARY));

		echo TbHtml::linkButton('Clear filter', array(
			'color'=>TbHtml::BUTTON_COLOR_INFO,
			'url'=>array('tvShow/index')));

		?>
	</div>
</div>
<?php

$this->endWidget();