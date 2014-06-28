<?php

/**
 * Displays media results as a grid with posters and labels.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
Yii::import('zii.widgets.CListView');

class ResultGrid extends CListView
{
	use ResultTrait;
	
	/**
	 * @var string the display mode context to use in the toggle switch. 
	 * Defaults to DisplayMode::CONTEXT_RESULTS 
	 */
	public $displayModeContext = DisplayMode::CONTEXT_RESULTS;
	
	public function beforeParentInit()
	{
		$this->itemsTagName = 'ul';
		$this->itemsCssClass = 'thumbnails item-grid';
		
		// trigger unveiling of images when the grid is updated through AJAX
		$this->afterAjaxUpdate = new CJavaScriptExpression("function() { $('.lazy').unveil(); }");
	}

	/**
	 * Override parent implementation and do nothing since we don't need this
	 */
	public function renderKeys()
	{

	}

}