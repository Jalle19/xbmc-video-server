<?php

/**
 * Base class for media flag widgets that use the media's stream details.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class MediaFlagStreamDetails extends MediaFlag
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
	 * Initializes the widget
	 */
	public function init()
	{
		parent::init();

		$this->audio = $this->streamDetails->audio[0];
		$this->video = $this->streamDetails->video[0];
	}

}
