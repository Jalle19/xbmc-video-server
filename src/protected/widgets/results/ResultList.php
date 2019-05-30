<?php

/**
 * Base class for a widget that displays media results as a simple list.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
Yii::import('bootstrap.widgets.TbGridView');

abstract class ResultList extends TbGridView
{
	use ResultTrait;
	
	/**
	 * Returns the column definitions
	 */
	abstract public function getColumnDefinitions();
	
	public function beforeParentInit()
	{
		// Configure columns
		$this->columns = $this->getColumnDefinitions();

		if ($this->dataProvider instanceof LibraryDataProvider) {
			$this->dataProvider->makeSortable();
		}
	}
	
	/**
	 * Returns the column definition for the label column
	 * @return array
	 */
	protected function getLabelColumn()
	{
		return array(
			'name'=>'label',
			'header'=>Yii::t('GenericList', 'Title'),
			'type'=>'html',
			'value'=>function($data) {
				/* @var $data Media */
				echo CHtml::link($data->label, Yii::app()->controller->createUrl('details', array('id'=>$data->getId())));
				echo $data->getWatchedIcon();
			},
		);
	}

	/**
	 * Returns the column definition for the year column
	 * @return array
	 */
	protected function getYearColumn()
	{
		return array(
			'name'=>'year',
			'header'=>Yii::t('GenericList', 'Year'),
			'value'=>function($data) {
				// Year is zero when it's not available
				if ($data->year !== 0)
					echo $data->year;
			}
		);
	}

	/**
	 * Returns the column definition for the genre column
	 * @return array
	 */
	protected function getGenreColumn()
	{
		return array(
			'name'=>'genre',
			'header'=>Yii::t('GenericList', 'Genre'),
			'value'=>function($data) {
				/* @var $data Media */
				return $data->getGenreString();
			}
		);
	}

}
