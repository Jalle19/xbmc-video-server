<?php

/**
 * Contains the functionality common to both ResultList and ResultGrid. They 
 * don't share a common ancestor so using a base class is not possible.
 * 
 * The beforeParentInit() method is used to inject custom initilization from 
 * derived classes.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
trait ResultTrait
{
	
	/**
	 * @var string the display mode context to use in the toggle switch. 
	 * Defaults to DisplayMode::CONTEXT_RESULTS 
	 */
	public $displayModeContext = DisplayMode::CONTEXT_RESULTS;
	
	/**
	 * Called right before parent::init() is called in the trait
	 */
	abstract public function beforeParentInit();

	/**
	 * Initializes the component
	 */
	public function init()
	{
		$this->id = 'result-list';
		
		// Configure the pager and template
		$this->template = '{summary} {items} {pager}';
		$this->pager = ResultHelper::getDefaultPagerConfiguration();

		// needed to update the URL when page is changed
		$this->enableHistory = true;
		
		$this->beforeParentInit();
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
