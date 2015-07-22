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
	
	public function getGenreType()
	{
		return VideoLibrary::GENRE_TYPE_TVSHOW;
	}

	/**
	 * @return array the request parameters
	 */
	public function getFilterDefinitions()
	{
		return $this->getCommonFilterDefinitions();
	}

}