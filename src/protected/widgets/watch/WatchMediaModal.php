<?php

Yii::import('bootstrap.widgets.TbModal');

/**
 * Base class for the modals that show watch/download buttons and links
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class WatchMediaModal extends TbModal
{

	/**
	 * @var Media the media operated on
	 */
	public $media;

	/**
	 * @return string the name of the class that should be rendered inside the 
	 * modal body
	 */
	abstract protected function getInnerWidgetClass();

	/**
	 * @return array the options for the Watch button
	 */
	abstract protected function getWatchButtonOptions();

	/**
	 * Initializes the widget
	 */
	public function init()
	{
		// Generate a unique ID. We can't rely on the built-in counter in 
		// CWidget::getId() because these modals may be rendered from AJAX 
		// request and the counter will be zero then
		$this->id = uniqid($this->getId());

		parent::init();

		TbHtml::addCssClass('watch-modal', $this->htmlOptions);
		$this->header = Yii::t('Movies', 'Watch / Download');
	}

	/**
	 * Renders the modal body
	 */
	public function renderModalBody()
	{
		echo CHtml::openTag('div', array('class'=>'modal-body'));

		$this->widget($this->getInnerWidgetClass(), array(
			'details'=>$this->media,
		));

		echo CHtml::closeTag('div');
	}

	/**
	 * Renders the button that triggers the modal
	 * @param array $htmlOptions (optional) options for the button
	 */
	public function renderTriggerButton($htmlOptions = array())
	{
		$commonOptions = array(
			'class'=>'fa fa-play watch-modal-button',
			'data-toggle'=>'modal',
			'data-target'=>'#'.$this->id);

		echo TbHtml::button(Yii::t('RetrieveMediaWidget', 'Watch'), array_merge(
						$commonOptions, $this->getWatchButtonOptions(), $htmlOptions));
	}

}
