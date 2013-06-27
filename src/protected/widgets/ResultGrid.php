<?php

/**
 * Sub-class of CListView. It fixes a bug in Yii where an empty data provider 
 * causes invalid HTML, which in turn triggers some weird layout bugs.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
Yii::import('zii.widgets.CListView');

class ResultGrid extends CListView
{
	
	/**
	 * Initializes the component. We use it to override the summary text.
	 */
	public function init()
	{
		parent::init();
		
		$this->summaryText = '{count} results';
	}

	/**
	 * Renders the data item list.
	 * 
	 * When the data provider is empty, Yii generates a <span> directly inside 
	 * a <ul> which is invalid an needs to be fixed.
	 */
	public function renderItems()
	{
		$data = $this->dataProvider->getData();

		// Get rid of that pesky dot at the end
		$emptyText = substr(Yii::t('zii', 'No results found.'), 0, strlen(Yii::t('zii', 'No results found.')) - 1);

		if (count($data) > 0)
			parent::renderItems();
		else
			echo CHtml::tag('div', array('class'=>'alert alert-block alert-error'), $emptyText);
	}

}