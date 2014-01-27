<?php

/**
 * ResultList implementation for movies
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class ResultListMovies extends ResultList
{

	public function getColumnDefinitions()
	{
		return array(
			$this->getLabelColumn(),
			$this->getYearColumn(),
			$this->getGenreColumn(),
			$this->getRatingColumn(),
			$this->getRuntimeColumn(),
		);
	}

	/**
	 * Returns the column definition for the rating column
	 * @return array
	 */
	private function getRatingColumn()
	{
		return array(
			'name'=>'rating',
			'header'=>'Rating',
			'value'=>function($data) {
				echo ResultHelper::formatRating($data->rating);
			}
		);
	}

	/**
	 * Returns the column definition for the runtime column
	 * @return array
	 */
	private function getRuntimeColumn()
	{
		return array(
			'name'=>'runtime',
			'header'=>'Runtime',
			'value'=>function($data) {
				echo ResultHelper::formatRuntime($data->runtime);
			}
		);
	}

}
