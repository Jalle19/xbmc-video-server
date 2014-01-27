<?php

/**
 * Renders the filter on the movie index page
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MovieFilter extends VideoFilter
{

	protected function renderControls()
	{
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

		echo $this->form->typeaheadFieldControlGroup($this->model, 'name', $typeaheadData);
		echo $this->form->dropDownListControlGroup($this->model, 'genre', 
				$this->model->getGenres(), array('prompt'=>''));
		
		echo $this->form->textFieldControlGroup($this->model, 'year', 
				array('style'=>'max-width: 40px;'));

		echo $this->form->textFieldControlGroup($this->model, 'rating', 
				array('style'=>'max-width: 40px;'));
		
		echo $this->form->dropDownListControlGroup($this->model, 'quality', 
				$this->model->getQualities(), 
				array('prompt'=>'', 'style'=>'width: 70px;'));

		echo $this->form->dropDownListControlGroup($this->model, 'watchedStatus', 
				VideoFilterForm::getWatchedStatuses(), 
				array('prompt'=>'', 'style'=>'width: 120px;'));

		echo $this->form->textFieldControlGroup($this->model, 'actor');
	}
	
	/**
	 * Returns an array containing all movie names
	 * @return array the names
	 */
	private function getMovieNames()
	{
		$names = array();
		
		foreach (VideoLibrary::getMovies() as $movie)
			$names[] = $movie->label;
		
		return $names;
	}

}
