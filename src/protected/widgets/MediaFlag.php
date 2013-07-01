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
	 * @var stdClass stream details for a media file
	 */
	public $streamDetails;

	/**
	 * @var stdClass the audio part of the stream details
	 */
	protected $audio;

	/**
	 * @var stdClass the video part of the stream details
	 */
	protected $video;

	/**
	 * @var string the base URL to the flag icons
	 */
	private $_iconBaseDir;

	/**
	 * Initializes the widget
	 */
	public function init()
	{
		parent::init();

		$this->audio = $this->streamDetails->audio[0];
		$this->video = $this->streamDetails->video[0];
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