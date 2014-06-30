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
	
	public function beforeParentInit()
	{
		$this->itemsTagName = 'ul';
		$this->itemsCssClass = 'thumbnails item-grid';
		
		// Scroll to the top of the list and trigger unveiling of images 
		// whenever the page is changed
		$this->afterAjaxUpdate = new CJavaScriptExpression("function() {
			location.hash = '#result-list';
			$('.lazy').unveil();
		}");
	}

	/**
	 * Override parent implementation and do nothing since we don't need this
	 */
	public function renderKeys()
	{

	}

}