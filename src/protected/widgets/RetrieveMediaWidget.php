<?php

/**
 * Base class for rendering a watch button and links to media items
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class RetrieveMediaWidget extends CWidget
{

	/**
	 * @var string the type of media that the links are for
	 */
	public $type;

	/**
	 * @var array the media links
	 */
	public $links;

	/**
	 * @var Media the media details
	 */
	public $details;
	
	/**
	 * @return string the category to use when logging download link clicks
	 */
	abstract protected function getLogCategory();
	
	/**
	 * @return string the category to use when logging download link clicks
	 */
	abstract protected function getLogMessage();
	
	/**
	 * @return string the URL for the Watch button
	 */
	abstract protected function getPlayListUrl();
	
	/**
	 * @return array the options for the Watch button
	 */
	abstract protected function getWatchButtonOptions();

	/**
	 * Runs the widget
	 */
	public function run()
	{
		// Don't render links for spectators
		if (Yii::app()->user->role === User::ROLE_SPECTATOR)
			return;
		
		if (!$this->checkLinks())
		{
			echo CHtml::tag('p', array('class'=>'missing-video-file'), TbHtml::icon(TBHtml::ICON_WARNING_SIGN).
					Yii::t('RetrieveMediaWidget', 'The file(s) for this item is not available'));

			return;
		}

		echo TbHtml::linkButton(Yii::t('RetrieveMediaWidget', 'Watch'), $this->getWatchButtonOptions());
		$this->renderLinks();
	}

	/**
	 * Checks that all media links are valid
	 * @return boolean
	 */
	private function checkLinks()
	{
		foreach ($this->links as $link)
			if (!$link)
				return false;

		return true;
	}
	
	/**
	 * @return the filename part of the full link to a file
	 */
	private function getDownloadName($link)
	{
		return urldecode(substr($link, strrpos($link, '%2f') + 3));
	}

	/**
	 * Returns the stream URL for the media. A direct link will be returned if 
	 * the user is on a mobile device or if the "singleFilePlaylist" setting 
	 * is enabled, otherwise the media playlist URL will be returned
	 * @return string the stream URL
	 */
	protected function getStreamUrl()
	{
		if (count($this->links) === 1 && (Setting::getBoolean('singleFilePlaylist') 
				|| (Browser::isMobile() || Browser::isTablet())))
		{
			return $this->links[0];
		}
		else
			return $this->getPlayListUrl();
	}

	/**
	 * Renders the download links
	 */
	private function renderLinks()
	{
		echo CHtml::openTag('div', array('class'=>'item-links'));

		$numLinks = count($this->links);
		$linkOptions = array(
			'class'=>'fontastic-icon-disc loggable-link',
			'data-log-category'=>$this->getLogCategory(),
			'data-log-message'=>htmlentities($this->getLogMessage()),
			'data-log-url'=>Yii::app()->controller->createUrl('/log/logEvent'),
		);

		foreach ($this->links as $k=> $link)
		{
			if ($numLinks == 1)
				$label = Yii::t('RetrieveMediaWidget', 'Download');
			else
				$label = Yii::t('RetrieveMediaWidget', 'Download (part #{partNumber})', array('{partNumber}'=>++$k));

			// Add the "download" attribute
			$linkOptions['download'] = $this->getDownloadName($link);
			
			echo CHtml::tag('p', array(), CHtml::link($label, $link, $linkOptions));
		}

		echo CHtml::closeTag('div');
	}

}