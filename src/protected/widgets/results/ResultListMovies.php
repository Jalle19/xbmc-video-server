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
			$this->getDateAddedColumn(),
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
			'header'=>Yii::t('MovieList', 'Rating'),
			'value'=>function($data) {
				/* @var $data Movie */
				echo $data->getRating();
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
			'header'=>Yii::t('GenericList', 'Runtime'),
			'value'=>function($data) {
				/* @var $data Movie */
				echo $data->getRuntimeString();
			}
		);
	}


	/**
	 * Returns the column definition for the dateadded column
	 * @return array
	 */
	private function getDateAddedColumn()
	{
		return [
			'name'   => 'dateadded',
			'header' => Yii::t('MovieList', 'Date added'),
			'value'  => function ($data) {
				/** @var Movie $data */
				echo $data->getDateAdded();
			},
		];
	}
}
