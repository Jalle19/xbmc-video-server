<?php

/**
 * Represents a single TV show season
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class Season extends Base implements IStreamable
{
	use WatchedIconTrait;
	use StreamableTrait;

	/**
	 * @var object the season art
	 */
	public $art;

	/**
	 * @var string the name of the show this season belongs to
	 */
	public $showtitle;

	/**
	 * @var int the number of episodes. Due to a typo in the XBMC API this is 
	 * missing an "s".
	 */
	public $episode;

	/**
	 * @var int the season number
	 */
	public $season;

	/**
	 * @var int the season ID
	 */
	public $seasonid;
	
	/**
	 * @var int the TV show ID
	 */
	public $tvshowid;

	public function getIdField()
	{
		return 'seasonid';
	}
	
	public function getDisplayName()
	{
		return $this->showtitle.' - '.$this->label;
	}

	/**
	 * @return mixed the season poster. If this is the first season of a show 
	 * and the artwork is not available we fall back to the show's artwork
	 */
	public function getArtwork()
	{
		if (isset($this->art->poster))
			return $this->art->poster;
		elseif ($this->season === 1)
		{
			$tvshow = VideoLibrary::getTVShowDetails($this->tvshowid, array('art', 'thumbnail'));
			return $tvshow->getArtwork();
		}

		return null;
	}
	
	public function getStreamableItems()
	{
		return VideoLibrary::getEpisodes($this->tvshowid, $this->season);
	}

	/**
	 * @return string a string representing the number of episodes in the season
	 */
	public function getEpisodesString()
	{
		return Yii::t('TVShows', '{num} episodes', array('{num}'=>$this->episode));
	}

}
