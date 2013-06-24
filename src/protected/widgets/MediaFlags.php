<?php

/**
 * Renders the media flags for a media
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
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