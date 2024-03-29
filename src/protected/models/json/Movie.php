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
class Movie extends File implements IStreamable
{
	use HasPremieredTrait;

	/**
	 * @var int
	 */
	public $movieid;

	/**
	 * @var string
	 */
	public $tagline;
	
	/**
	 * @var array the directors for this movie
	 */
	public $director;

	/**
	 * @var string the date the movie was added to the library 
	 */
	public $dateadded;

	/**
	 * @var object the movie art
	 */
	public $art;

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
	 * @return string the first credited director, or an empty string
	 */
	public function getDirector()
	{
		if ($this->director === null || count($this->director) === 0)
			return '';
		else
			return $this->director[0];
	}

	/**
	 * @return string the URL to the movie's IMDb page
	 */
	public function getIMDbUrl()
	{
		return 'http://www.imdb.com/title/'.$this->imdbnumber.'/';
	}

	/**
	 * @return string
	 */
	public function getDateAdded()
	{
		return $this->dateadded;
	}

	public function getArtwork()
	{
		if (isset($this->art->poster)) {
			return $this->art->poster;
		}

		return parent::getArtwork();
	}
}
