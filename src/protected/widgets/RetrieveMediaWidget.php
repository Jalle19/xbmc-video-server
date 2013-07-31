<?php

/**
 * Renders a Watch button and links to the media items.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class RetrieveMediaWidget extends CWidget
{

	const MEDIA_TYPE_MOVIE = 'movie';
	const MEDIA_TYPE_TVSHOW = 'tvshow';

	/**
	 * @var string the type of media that the links are for
	 */
	public $type;

	/**
	 * @var array the media links
	 */
	public $links;

	/**
	 * @var stdClass the media details
	 */
	public $details;

	/**
	 * Initializes the widget
	 * @throws Exception if required attributes are undefined
	 */
	public function init()
	{
		foreach (array('type', 'links', 'details') as $attribute)
			if (!isset($this->{$attribute}))
				throw new Exception($attribute.' must be defined');
	}

	/**
	 * Runs the widget
	 */
	public function run()
	{
		if (!$this->checkLinks())
		{
			echo CHtml::tag('p', array('class'=>'missing-video-file'), TbHtml::icon(TBHtml::ICON_WARNING_SIGN).
					'The file(s) for this item is not available');

			return;
		}

		echo TbHtml::linkButton('Watch', $this->getWatchButtonOptions());
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
	 * Returns the stream URL for the media. If the media has a single link and 
	 * the "singleFilePlaylist" setting is enabled, the direct link will be 
	 * returned, otherwise a URL to a playlist will be returned
	 * @return string the stream URL
	 */
	private function getStreamUrl()
	{
		if (count($this->links) === 1 && Setting::getValue('singleFilePlaylist'))
			return $this->links[0];
		else
		{
			if ($this->type === self::MEDIA_TYPE_MOVIE)
				return array('getMoviePlaylist', 'movieId'=>$this->details->movieid);
			elseif ($this->type === self::MEDIA_TYPE_TVSHOW)
				return array('getEpisodePlaylist', 'episodeId'=>$this->details->episodeid);
		}
	}

	/**
	 * Determines the options for the Watch button
	 * @return array the options
	 */
	private function getWatchButtonOptions()
	{
		// General options
		$options = array(
			'url'=>$this->getStreamUrl(),
			'class'=>'fontastic-icon-play',
		);

		// Options that vary by media type
		if ($this->type === self::MEDIA_TYPE_MOVIE)
		{
			$mediaSpecificOptions = array(
				'color'=>TbHtml::BUTTON_COLOR_SUCCESS,
				'size'=>TbHtml::BUTTON_SIZE_LARGE,
			);
		}
		elseif ($this->type === self::MEDIA_TYPE_TVSHOW)
		{
			$mediaSpecificOptions = array(
				'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
				'size'=>TbHtml::BUTTON_SIZE_SMALL,
			);
		}

		return array_merge($options, $mediaSpecificOptions);
	}

	/**
	 * Renders the download links
	 */
	private function renderLinks()
	{
		echo CHtml::openTag('div', array('class'=>'item-links'));

		$numLinks = count($this->links);
		$linkOptions = array('class'=>'fontastic-icon-disc');

		foreach ($this->links as $k=> $link)
		{
			if ($numLinks == 1)
				$label = 'Download';
			else
				$label = 'Download (part #'.(++$k).')';

			echo CHtml::tag('p', array(), CHtml::link($label, $link, $linkOptions));
		}

		echo CHtml::closeTag('div');
	}

}