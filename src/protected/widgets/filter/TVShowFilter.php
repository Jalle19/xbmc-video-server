<?php

/**
 * Renders the filter on the TV show index page
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class TVShowFilter extends VideoFilter
{
	
	protected function renderControls()
	{
		$ctrl = Yii::app()->controller;
		
		echo $this->form->typeaheadFieldControlGroup($this->model, 'name', array(
			'prefetch'=>$ctrl->createUrl('typeahead/getTVShowNames')));

		echo $this->form->dropDownListControlGroup($this->model, 'genre', 
				$this->model->getGenres(), array('empty'=>' '));
		
		echo $this->form->dropDownListControlGroup($this->model, 'watchedStatus', 
				VideoFilterForm::getWatchedStatuses(), 
				array('empty'=>' ', 'style'=>'width: 120px;'));
		
		echo $this->form->typeaheadFieldControlGroup($this->model, 'actor', array(
			'prefetch'=>$ctrl->createUrl('typeahead/getActorNames', array('mediaType'=>Actor::MEDIA_TYPE_TVSHOW))));
	}

}
