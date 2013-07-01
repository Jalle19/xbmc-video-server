<?php

/**
 * Renders a collapsed list of seasons with episodes inside (accordion style)
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class SeasonAccordion extends CWidget
{

	/**
	 * @var array the items for the list
	 */
	public $items = array();

	/**
	 * @var array options for the container
	 */
	public $htmlOptions = array();

	/**
	 * Initializes the widget
	 */
	public function init()
	{
		$this->htmlOptions = TbHtml::addClassName('accordion', $this->htmlOptions);

		parent::init();
	}

	/**
	 * Renders the widget. If there are no items, nothing will be rendered.
	 * TODO: Reuse TbCollapse somehow
	 */
	public function run()
	{
		$itemCount = count($this->items);

		if ($itemCount === 0)
			return;

		echo CHtml::openTag('div', $this->htmlOptions);

		foreach ($this->items as $k=> $item)
		{
			$id = __CLASS__.'_'.$this->id.'_'.$k;

			$label = CHtml::link($item['label'], '#'.$id, array(
						'class'=>'accordion-toggle',
						'data-toggle'=>'collapse',
						'data-parent'=>$this->id));

			$bodyOptions = array('class'=>'accordion-body collapse', 'id'=>$id);
			if ($itemCount === 1)
				$bodyOptions = TbHtml::addClassName('in', $bodyOptions);

			echo CHtml::openTag('div', array('class'=>'accordion-group'));
			echo CHtml::tag('div', array('class'=>'accordion-heading'), $label);
			echo CHtml::openTag('div', $bodyOptions);
			echo CHtml::tag('div', array('class'=>'accordion-inner'), $item['content']);
			echo CHtml::closeTag('div');
			echo CHtml::closeTag('div');
		}

		echo CHtml::closeTag('div');
	}

}