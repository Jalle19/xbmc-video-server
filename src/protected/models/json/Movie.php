<?php

/**
 * Represents a movie
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 * 
 * Virtual class properties:
 * 
 * @property int $votes
 */
class Movie extends Media
{

	/**
	 * @var int
	 */
	public $movieid;

	/**
	 * @var string
	 */
	public $file;

	/**
	 * @var string
	 */
	public $tagline;

	/**
	 * @var int
	 */
	private $_votes;

	public function getDisplayName()
	{
		return $this->label.' ('.$this->year.')';
	}

	/**
	 * @return int the vote count
	 */
	public function getVotes()
	{
		return $this->_votes;
	}

	/**
	 * Setter for votes. The value returned from the API is a string which 
	 * we'll convert to an integer.
	 * @param string $votes
	 */
	public function setVotes($votes)
	{
		$this->_votes = (int)str_replace(',', '', $votes);
	}

	/**
	 * @return string the URL to the movie's IMDb page
	 */
	public function getIMDbUrl()
	{
		return 'http://www.imdb.com/title/'.$this->imdbnumber.'/';
	}

}
