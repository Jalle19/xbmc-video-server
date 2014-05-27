<?php

/**
 * Represents a single TV show season
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class Season extends Base
{

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

	public function getDisplayName()
	{
		return $this->label;
	}

	public function getId()
	{
		return $this->seasonid;
	}

	/**
	 * @return mixed the poster or null if it's not available
	 */
	public function getArtwork()
	{
		if (isset($this->art->poster))
			return $this->art->poster;

		return null;
	}

	/**
	 * @return string a string representing the number of episodes in the season
	 */
	public function getEpisodesString()
	{
		return Yii::t('Season', '{num} episodes', array('{num}'=>$this->episode));
	}

}
