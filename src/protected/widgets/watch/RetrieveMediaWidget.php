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
	 * @var File the media file
	 */
	public $details;
	
	/**
	 * @var ItemLink[] the media links
	 */
	private $_links;
	
	/**
	 * @return string the category to use when logging download link clicks
	 */
	abstract protected function getLogMessage();
	
	/**
	 * @return string the action for retrieving an item's playlist
	 */
	abstract protected function getPlayListAction();
	
	/**
	 * Initializes the widget
	 */
	public function init()
	{
		$this->_links = $this->details->getItemLinks();
	}

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
		
		$this->renderForm();
	}

	/**
	 * Checks that all media links are valid
	 * @return boolean
	 */
	private function checkLinks()
	{
		foreach ($this->_links as $link)
			if (!$link->url)
				return false;

		return true;
	}
	
	/**
	 * @param string $link
	 * @return string the filename part of the full link to a file
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
		if (count($this->_links) === 1 && (Setting::getBoolean('singleFilePlaylist') 
				|| (Browser::isMobile() || Browser::isTablet())))
		{
			return $this->_links[0]->url;
		}
		else
			return $this->getPlayListAction();
	}
	
	/**
	 * @return array options for the watch buttons displayed
	 */
	private function getWatchButtonsOptions()
	{
		return array(
			'color'=>TbHtml::BUTTON_COLOR_SUCCESS,
			'size'=>TbHtml::BUTTON_SIZE_LARGE,
			'url'=>$this->getStreamUrl(),
			'class'=>'fa fa-play');
	}
	
	/**
	 * Renders the form that contains the buttons and links to watch/download 
	 * and item
	 */
	private function renderForm()
	{
		// Render the form with all the retrieval options
		echo CHtml::beginForm($this->getPlayListAction(), 'get');
		echo CHtml::hiddenField('id', $this->details->getId());
		
		// Show the "Play in XBMC" button to administrators
		if (Yii::app()->user->role === User::ROLE_ADMIN)
		{
			?>
			<section>
				<?php $this->renderPlayOnBackendButton(); ?>
			</section>
			<?php
		}
		
		// Show the "Play in browser" button when applicable
		$helper = new MediaInfoHelper($this->details);
		
		if (!$helper->needsTranscoding() && count($this->_links) === 1)
		{
			?>
			<section>
				<?php $this->renderWatchInBrowserButton(); ?>
			</section>
			<?php
		}
		
		?>
		<section>
			<?php $this->renderWatchButton(); ?>
		</section>
		<?php
		
		// Render the download links and close the form
		$this->renderLinks();
		echo CHtml::endForm();
	}

	/**
	 * Renders the download links
	 */
	private function renderLinks()
	{
		echo CHtml::openTag('div', array('class'=>'item-links'));

		$numLinks = count($this->_links);
		$linkOptions = array(
			'class'=>'fa fa-floppy-o loggable-link',
			'data-log-category'=>get_class($this->owner),
			'data-log-message'=>htmlentities($this->getLogMessage()),
			'data-log-url'=>Yii::app()->controller->createUrl('/log/logEvent'),
		);

		foreach ($this->_links as $k=> $link)
		{
			if ($numLinks == 1)
				$label = Yii::t('RetrieveMediaWidget', 'Download');
			else
				$label = Yii::t('RetrieveMediaWidget', 'Download (part #{partNumber})', array('{partNumber}'=>++$k));

			// Add the "download" attribute
			$linkOptions['download'] = $this->getDownloadName($link->url);
			
			echo CHtml::tag('p', array(), CHtml::link($label, $link->url, $linkOptions));
		}

		echo CHtml::closeTag('div');
	}
	
	/**
	 * Renders the "Play in XBMC" button
	 */
	private function renderPlayOnBackendButton()
	{
		$playOnBackendButtonOptions = array(
			'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
			'size'=>TbHtml::BUTTON_SIZE_LARGE,
			'url'=>array('playOnBackend', 'file'=>$this->details->file),
			'class'=>'fa fa-desktop');

		echo TbHtml::linkButton(Yii::t('RetrieveMediaWidget', 'Play in XBMC'), $playOnBackendButtonOptions);
	}

	/**
	 * Renders the "Watch as playlist" button
	 */
	private function renderWatchButton()
	{
		// Select the default playlist format by default
		$dropdownOptions = array(
			Setting::getString('playlistFormat')=>array('selected'=>'selected'));

		echo TbHtml::dropDownListControlGroup('playlistFormat', 'playlistFormat', PlaylistFactory::getTypes(), array(
			'label'=>Yii::t('Settings', 'Playlist format'),
			'options'=>$dropdownOptions));

		echo TbHtml::submitButton(Yii::t('RetrieveMediaWidget', 'Watch as playlist'), $this->getWatchButtonsOptions());
	}
	
	/**
	 * Renders the "Watch as in browser" button
	 */
	private function renderWatchInBrowserButton()
	{
		// Swap the button URL for the first item link
		$buttonOptions = $this->getWatchButtonsOptions();
		$buttonOptions['url'] = array('watchInBrowser', 'url'=>$this->_links[0]->url);
		
		echo TbHtml::linkButton(Yii::t('RetrieveMediaWidget', 'Watch in browser'), $buttonOptions);
	}

}