<?php

Yii::import('bootstrap.widgets.TbModal');

/**
 * The video player widget. It is basically a standard modal which contains a 
 * <video> tag.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class VideoPlayer extends TbModal
{
	
	/**
	 * The HTML container ID that the player uses
	 */
	const HTML_ID = 'video-player';

	/**
	 * @var stdClass the video details
	 */
	public $videoDetails;
	
	/**
	 * @var array the video links
	 */
	public $videoLinks;

	/**
	 * Initializes the widget
	 */
	public function init()
	{
		/* @var $cs CClientScript */
		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile('//vjs.zencdn.net/4.4/video.js', CClientScript::POS_END);
		$cs->registerCssFile('//vjs.zencdn.net/4.4/video-js.css');

		TbHtml::addCssClass('video-player', $this->htmlOptions);
		
		$this->header = $this->videoDetails->label;
		$this->content = $this->renderPlayer();
		$this->footer = array(
			TbHtml::button('Close', array(
				'data-dismiss'=>'modal',
				'class'=>'close-video-player-button',
			)),
		);

		parent::init();
	}
	
	/**
	 * Returns the transcoded URLs for the movie (one per preset)
	 * @return array
	 */
	private function getVideoUrls()
	{
		$sourceUrl = $this->videoLinks[0];
		$videoUrls = array();
		$transcoderPresets = TranscoderPreset::model()->findAll();

		/* @var $ctrl CController */
		$ctrl = Yii::app()->controller;

		foreach ($transcoderPresets as $preset)
		{
			$videoUrls[$preset->getMIMEType()] = $ctrl->createUrl('transcode/transcode', array(
				'source'=>$sourceUrl,
				'presetId'=>$preset->id));
		}

		return $videoUrls;
	}

	/**
	 * Returns the HTML for the <video> tag
	 * @return string
	 */
	private function renderPlayer()
	{
		ob_start();

		echo CHtml::openTag('video', array('class'=>'video-js vjs-default-skin', 'controls'=>'controls', 'preload'=>'none'));

		foreach ($this->getVideoUrls() as $mimeType=> $videoUrl)
			echo CHtml::tag('source', array('src'=>$videoUrl, 'type'=>$mimeType));

		echo CHtml::closeTag('video');

		return ob_get_clean();
	}

}
