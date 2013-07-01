<?php

/**
 * Renders the media flags for a media
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MediaFlags extends CWidget
{
	
	/**
	 * @var stdClass stream details for a media file
	 */
	public $streamDetails;
	
	/**
	 * Runs the widget
	 */
	public function run()
	{
		// Some media don't have any stream details, we can't render any flags 
		// for those
		if (count($this->streamDetails->audio) == 0 
				|| count($this->streamDetails->video) == 0)
		{
			return;
		}
		
		?>
		<div class="media-flags">
			<div class="flag-section">
				<?php $this->widget('MediaFlagResolution', array(
				'streamDetails'=>$this->streamDetails));
					$this->widget('MediaFlagVideoCodec', array(
				'streamDetails'=>$this->streamDetails)); ?>
			</div>
			
			<div class="flag-section">
				<?php $this->widget('MediaFlagAudioCodec', array(
						'streamDetails'=>$this->streamDetails));
					$this->widget('MediaFlagAudioChannels', array(
						'streamDetails'=>$this->streamDetails)); ?>
			</div>
			
			<div class="flag-section">
				<?php $this->widget('MediaFlagAspect', array(
						'streamDetails'=>$this->streamDetails)); ?>
			</div>
		</div>
		<?php
	}

}