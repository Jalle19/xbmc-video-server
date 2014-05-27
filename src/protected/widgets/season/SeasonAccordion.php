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
		TbHtml::addCssClass('accordion', $this->htmlOptions);

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
			$contentId = $id.'_content';
			
			$linkOptions = array(
				'class'=>'accordion-toggle episode-toggle',
				'data-content-id'=>$contentId,
				'data-toggle'=>'collapse',
				'data-parent'=>$this->id);

			// Add content-url data attributes to the link when available
			if (isset($item['contentUrl']))
				$linkOptions['data-content-url'] = $item['contentUrl'];

			$label = CHtml::link($item['season']->getDisplayName(), '#'.$id, $linkOptions);

			$bodyOptions = array('class'=>'accordion-body collapse', 'id'=>$id);
			if ($itemCount === 1)
				TbHtml::addCssClass('in', $bodyOptions);

			echo CHtml::openTag('div', array('class'=>'accordion-group'));
			echo CHtml::tag('div', array('class'=>'accordion-heading'), $label);
			echo CHtml::openTag('div', $bodyOptions);
			echo CHtml::tag('div', array('id'=>$contentId, 
				'class'=>'accordion-inner'), $item['content']);
			echo CHtml::closeTag('div');
			echo CHtml::closeTag('div');
		}

		echo CHtml::closeTag('div');
	}

}