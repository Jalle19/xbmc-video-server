<?php

/**
 * Form model for the TV show filter
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class TVShowFilterForm extends VideoFilterForm
{

	/**
	 * Populates and returns the list of genres
	 * @return array
	 */
	public function getGenres()
	{
		if (empty($this->_genres))
		{
			$genres = VideoLibrary::getGenres(VideoLibrary::GENRE_TYPE_TVSHOW);

			foreach ($genres as $genre)
				$this->_genres[$genre->label] = $genre->label;
		}

		return $this->_genres;
	}

	/**
	 * Returns the request parameters for this filter. It can be used to filter 
	 * results from VideoLibrary::getMovies()
	 * @return array the request parameters
	 */
	public function getFilterDefinitions()
	{
		// Build the filter
		$filter = array();

		// Include partial matches
		$filter['title'] = array(
			'operator'=>'contains',
			'value'=>$this->name);

		$filter['genre'] = array(
			'operator'=>'is',
			'value'=>$this->genre);

		return $filter;
	}

}