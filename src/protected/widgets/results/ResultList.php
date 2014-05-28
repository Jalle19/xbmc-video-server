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
	
	/**
	 * Initializes the component
	 */
	public function init()
	{
		// Configure the pager and override the default template
		$this->template = '{summary} {items} {pager}';
		$this->pager = $this->pager = ResultHelper::getDefaultPagerConfiguration();
		$this->pagerCssClass = 'pager';
		
		// Make the list sortable
		$this->dataProvider->makeSortable();
		
		// Configure columns
		$this->columns = $this->getColumnDefinitions();
		
		parent::init();
	}
	
	/**
	 * Returns the column definitions
	 */
	abstract public function getColumnDefinitions();
	
	/**
	 * @see ResultGrid::ResultGrid()
	 */
	public function renderItems()
	{
		// Get rid of that pesky dot at the end
		if ($this->emptyText === null)
			$this->emptyText = substr(Yii::t('GenericList', 'No results found.'), 0, strlen(Yii::t('GenericList', 'No results found.')) - 1);

		if ($this->dataProvider->totalItemCount > 0)
			parent::renderItems();
		else
			echo CHtml::tag('div', array('class'=>'alert alert-block alert-error'), $this->emptyText);
	}

	/**
	 * @see ResultGrid::renderSummary()
	 */
	public function renderSummary()
	{
		// Render the actual summary into a variable
		ob_start();
		parent::renderSummary();
		$summaryContent = ob_get_clean();
		
		ResultHelper::renderDisplayModeToggle($summaryContent, DisplayMode::CONTEXT_RESULTS);
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
				return CHtml::link($data->label, Yii::app()->controller->createUrl('details', array('id'=>$data->getId())));
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
