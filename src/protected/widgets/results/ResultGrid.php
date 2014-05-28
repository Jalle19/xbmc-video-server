<?php

/**
 * Displays media results as a grid with posters and labels.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 * 
 * TODO: Combine ResultGrid and ResultList somehow to reduce the amount of code duplication
 */
Yii::import('zii.widgets.CListView');

class ResultGrid extends CListView
{
	
	/**
	 * @var string the display mode context to use in the toggle switch. 
	 * Defaults to DisplayMode::CONTEXT_RESULTS 
	 */
	public $displayModeContext = DisplayMode::CONTEXT_RESULTS;

	/**
	 * Initializes the component
	 */
	public function init()
	{
		$this->pager = ResultHelper::getDefaultPagerConfiguration();
		$this->itemsTagName = 'ul';
		$this->itemsCssClass = 'thumbnails item-grid';

		parent::init();
	}

	/**
	 * Renders the data item list.
	 * 
	 * When the data provider is empty, Yii generates a <span> directly inside 
	 * a <ul> which is invalid an needs to be fixed.
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
	 * Override parent implementation and do nothing since we don't need this
	 */
	public function renderKeys()
	{

	}
	
	/**
	 * Override parent implementation and do nothing since we don't need this
	 */
	public function registerClientScript()
	{
		
	}
	
	/**
	 * Renders the summary and the display mode toggle.
	 */
	public function renderSummary()
	{
		// Render the actual summary into a variable
		ob_start();
		parent::renderSummary();
		$summaryContent = ob_get_clean();
		
		ResultHelper::renderDisplayModeToggle($summaryContent, $this->displayModeContext);
	}

}