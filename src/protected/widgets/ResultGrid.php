<?php

/**
 * Sub-class of CListView. It fixes a bug in Yii where an empty data provider 
 * causes invalid HTML, which in turn triggers some weird layout bugs.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
Yii::import('zii.widgets.CListView');

class ResultGrid extends CListView
{
	
	/**
	 * Initializes the component
	 */
	public function init()
	{
		// Configure the pager
		$this->pager = array(
			'class'=>'bootstrap.widgets.TbPager',
			'maxButtonCount'=>10,
			'htmlOptions'=>array(
				'align'=>TbHtml::PAGINATION_ALIGN_RIGHT,
			),
		);
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
			$this->emptyText = substr(Yii::t('zii', 'No results found.'), 0, strlen(Yii::t('zii', 'No results found.')) - 1);

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

}