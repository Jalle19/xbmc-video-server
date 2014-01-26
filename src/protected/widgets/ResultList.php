<?php

/**
 * Displays media results as a simple list.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
Yii::import('bootstrap.widgets.TbGridView');

class ResultList extends TbGridView
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
		$this->columns = array(
				array(
					'class'=>'CLinkColumn',
					'header'=>'Title',
					'labelExpression'=>function($data) {
						return $data->label;
					},
					'urlExpression'=>function($data) {
						// Determine the name of the ID property
						if (isset($data->movieid))
							$id = $data->movieid;
						elseif (isset($data->tvshowid))
							$id = $data->tvshowid;
						else
							$id = null;
						
						return Yii::app()->controller->createUrl('details', array('id'=>$id));
					}
				),
				array(
					'name'=>'year',
					'header'=>'Year',
					'value'=>function($data) {
						// Year is zero when it's not available
						if ($data->year !== 0)
							echo $data->year;
					}
				),
				array(
					'name'=>'genre',
					'header'=>'Genre',
					'value'=>function($data) {
						return implode(' / ', $data->genre);
					}
				)
			);
		
		parent::init();
	}
	
	/**
	 * @see ResultGrid::ResultGrid()
	 */
	public function renderItems()
	{
		// Get rid of that pesky dot at the end
		if ($this->emptyText === null)
			$this->emptyText = substr(Yii::t('zii', 'No results found.'), 0, strlen(Yii::t('zii', 'No results found.')) - 1);

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
		
		ResultHelper::renderDisplayModeToggle($summaryContent);
	}

}
