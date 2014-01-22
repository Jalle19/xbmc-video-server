<?php

/**
 * Base class for media flag widgets
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class MediaFlag extends CWidget
{

	/**
	 * @var string the media file name
	 */
	public $file;

	/**
	 * @var string the base URL to the flag icons
	 */
	private $_iconBaseDir;

	/**
	 * Initializes the widget
	 */
	public function init()
	{
		$this->_iconBaseDir = Yii::app()->baseUrl.'/images/xbmc-media-flags';
	}

	/**
	 * Runs the widget. If an icon can be determined it will be rendered.
	 */
	public function run()
	{
		$icon = $this->getIcon();

		if ($icon !== false)
			echo CHtml::image($this->_iconBaseDir.'/'.$icon.'.png');
	}

	/**
	 * Returns the name of the icon file that should be displayed, without the 
	 * file extension, e.g. "50px-720"
	 * @return string the icon name
	 */
	abstract protected function getIcon();
}
