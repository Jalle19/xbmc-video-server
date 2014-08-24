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
		echo $this->form->typeaheadFieldControlGroup($this->model, 'name', 
				$this->getMovieNameTypeaheadData());
		
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

		echo $this->form->typeaheadFieldControlGroup($this->model, 'actor', 
				$this->getActorNameTypeaheadData());
	}
	
	/**
	 * Returns the typeahead data for the movie name field. The API call cache 
	 * is used when it is enabled to speed up the retrieval.
	 * @return string the list of movies encoded as JavaScript
	 */
	private function getMovieNameTypeaheadData()
	{
		// Cache the encoded JavaScript if the "cache API calls" setting is enabled
		if (Setting::getBoolean('cacheApiCalls'))
		{
			$cacheId = 'MovieFilterMovieNameTypeahead';
			$typeaheadData = Yii::app()->apiCallCache->get($cacheId);

			if ($typeaheadData === false)
			{
				$typeaheadData = CJavaScript::encode($this->getTypeaheadNames(VideoLibrary::getMovies()));
				Yii::app()->apiCallCache->set($cacheId, $typeaheadData);
			}
		}
		else
			$typeaheadData = CJavaScript::encode($this->getTypeaheadNames(VideoLibrary::getMovies()));

		return $typeaheadData;
	}
	
	/**
	 * Returns the typeahead data for the movie name field. The API call cache 
	 * is used when it is enabled to speed up the retrieval.
	 * @return string the list of movies encoded as JavaScript
	 */
	private function getActorNameTypeaheadData()
	{
		// Cache the encoded JavaScript if the "cache API calls" setting is enabled
		if (Setting::getBoolean('cacheApiCalls'))
		{
			$cacheId = 'MovieFilterActorNameTypeahead';
			$typeaheadData = Yii::app()->apiCallCache->get($cacheId);

			if ($typeaheadData === false)
			{
				$typeaheadData = CJavaScript::encode($this->getActorNames());
				Yii::app()->apiCallCache->set($cacheId, $typeaheadData);
			}
		}
		else
			$typeaheadData = CJavaScript::encode($this->getActorNames());

		return $typeaheadData;
	}

	/**
	 * @return array a list of all actor names
	 */
	private function getActorNames()
	{
		$names = array();

		foreach (VideoLibrary::getActors(Actor::MEDIA_TYPE_MOVIE) as $actor)
			$names[] = $actor->name;

		return $names;
	}

}
