<?php

/**
 * Renders the filter on the movie index page
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 * 
 * @property MovieFilterForm $model
 */
class MovieFilter extends VideoFilter
{

	protected function renderControls()
	{
		$ctrl = Yii::app()->controller;
		
		echo $this->form->typeaheadFieldControlGroup($this->model, 'name', array(
			'prefetch'=>$ctrl->createUrl('typeahead/getMovieNames')));
		
		echo $this->form->dropDownListControlGroup($this->model, 'genre', 
				$this->model->getGenres(), array('empty'=>' '));
		
		echo $this->form->textFieldControlGroup($this->model, 'year', 
				array('style'=>'max-width: 40px;'));

		echo $this->form->textFieldControlGroup($this->model, 'rating', 
				array('style'=>'max-width: 40px;'));
		
		echo $this->form->dropDownListControlGroup($this->model, 'quality', 
				$this->model->getQualities(), 
				array('empty'=>' ', 'style'=>'width: 70px;'));

		echo $this->form->dropDownListControlGroup($this->model, 'watchedStatus', 
				VideoFilterForm::getWatchedStatuses(), 
				array('empty'=>' ', 'style'=>'width: 120px;'));

		echo $this->form->typeaheadFieldControlGroup($this->model, 'actor', array(
			'prefetch'=>$ctrl->createUrl('typeahead/getActorNames', array('mediaType'=>Actor::MEDIA_TYPE_MOVIE))));
		
		echo $this->form->typeaheadFieldControlGroup($this->model, 'director', array(
			'prefetch'=>$ctrl->createUrl('typeahead/getDirectorNames')));
	}
	
}
