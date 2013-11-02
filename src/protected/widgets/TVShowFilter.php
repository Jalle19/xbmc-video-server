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
		echo $this->form->typeaheadFieldControlGroup($this->model, 'name', 
			CJavaScript::encode($this->getTVShowNames()));

		echo $this->form->dropDownListControlGroup($this->model, 'genre', 
				$this->model->getGenres(), array('prompt'=>''));
		
		echo $this->form->dropDownListControlGroup($this->model, 'watchedStatus', 
				VideoFilterForm::getWatchedStatuses(), 
				array('prompt'=>'', 'style'=>'width: 120px;'));
	}
	
	/**
	 * Returns an array containing the names of all TV shows
	 * @return array the names
	 */
	private function getTVShowNames()
	{
		$names = array();

		foreach (VideoLibrary::getTVShows() as $movie)
			$names[] = $movie->label;

		return $names;
	}

}
