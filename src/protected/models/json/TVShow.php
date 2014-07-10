<?php

/**
 * Represents a TV show
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class TVShow extends Media
{
	use StreamableTrait;

	/**
	 * @var object
	 */
	public $art;

	/**
	 * @var int
	 */
	public $tvshowid;

	public function getDisplayName()
	{
		$name = $this->label;
		if (!empty($this->year))
			$name .= ' ('.$this->year.')';

		return $name;
	}

	/**
	 * @return string the artwork for the show
	 */
	public function getArtwork()
	{
		// Prefer the poster when it's available
		if (isset($this->art->poster))
			return $this->art->poster;
		else
			return $this->thumbnail;
	}
	
	public function getStreamableItems()
	{
		$items = array();
		$seasons = VideoLibrary::getSeasons($this->tvshowid);
		
		// Get all episodes from all the seasons
		foreach($seasons as $season)
		{
			$episodes = VideoLibrary::getEpisodes($this->tvshowid, $season->season);
			$items = array_merge($items, $episodes);
		}
		
		return $items;
	}
	
	/**
	 * @return string the URL to the show's TVDB page
	 */
	public function getTVDBUrl()
	{
		return 'http://www.thetvdb.com/?tab=series&amp;id='.$this->imdbnumber;
	}

}
