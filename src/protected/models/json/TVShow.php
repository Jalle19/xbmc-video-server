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
	
	/**
	 * @return string the URL to the show's TVDB page
	 */
	public function getTVDBUrl()
	{
		return 'http://www.thetvdb.com/?tab=series&amp;id='.$this->imdbnumber;
	}

}
