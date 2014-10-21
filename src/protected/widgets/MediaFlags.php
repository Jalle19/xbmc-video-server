<?php

Yii::import('application.widgets.flags.*');

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
	 * @var File the media file
	 */
	public $file;
	
	/**
	 * Runs the widget
	 */
	public function run()
	{
		echo CHtml::openTag('div', array('class'=>'media-flags'));
		
		// Some media don't have any stream details, we have to skip the flags
		// that depend on them.
		$helper = new MediaInfoHelper($this->file);
		
		if ($helper->hasMediaInfo())
		{
			$streamDetails = $this->file->streamdetails;
			
			?>
			<div class="flag-section">
				<?php $this->widget('MediaFlagResolution', array(
				'streamDetails'=>$streamDetails));
					$this->widget('MediaFlagVideoCodec', array(
				'streamDetails'=>$streamDetails)); ?>
			</div>
			
			<div class="flag-section">
				<?php $this->widget('MediaFlagAudioCodec', array(
						'streamDetails'=>$streamDetails));
					$this->widget('MediaFlagAudioChannels', array(
						'streamDetails'=>$streamDetails)); ?>
			</div>
			
			<div class="flag-section">
				<?php $this->widget('MediaFlagAspect', array(
						'streamDetails'=>$streamDetails)); ?>
			</div>
			<?php
		}
		
		?>
		<div class="flag-section">
			<?php $this->widget('MediaFlagVideoSource', array(
				'file'=>$this->file->file));
			$this->widget('MediaFlagAudioSource', array(
				'file'=>$this->file->file)); ?>
		</div>
		<?php
		
		echo CHtml::closeTag('div');
	}

}
