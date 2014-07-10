<?php

/**
 * Represents a TV show episode
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class Episode extends Media
{
	use StreamableTrait;
	
	/**
	 * @var int
	 */
	public $episodeid;
	
	/**
	 * @var int
	 */
	public $tvshowid;
	
	/**
	 * @var int
	 */
	public $episode;
	
	/**
	 * @var string
	 */
	public $showtitle;
	
	/**
	 * @var int
	 */
	public $season;
	
	/**
	 * @var string the episode title (not the same as the label)
	 */
	public $title;
	
	public function getIdField()
	{
		return 'episodeid';
	}
	
	public function getStreamableItems()
	{
		return array($this);
	}
	
	/**
	 * Returns a string based on season and episode number, e.g. 1x05.
	 * @return string
	 */
	public function getEpisodeString()
	{
		return $this->season.'x'.str_pad($this->episode, 2, '0', STR_PAD_LEFT);
	}
	
}
