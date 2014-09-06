<?php

/**
 * Base class for all media items (movies, TV shows, episodes). This class 
 * contains fields that can be found in all the base classes.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 * 
 * Properties and methods from traits
 * @property string $file the item file
 * @method ItemLink[] getItemLinks() the item links
 * @method string getWatchedIcon() the HTML for a watched icon
 */
abstract class Media extends Base
{
	use WatchedIconTrait;

	/**
	 * @var float
	 */
	public $rating;

	/**
	 * @var int
	 */
	public $runtime;

	/**
	 * @var int
	 */
	public $year;

	/**
	 * @var array
	 */
	public $genre = array();

	/**
	 * @var Actor[]
	 */
	public $cast;

	/**
	 * @var string
	 */
	public $mpaa;

	/**
	 * @var string
	 */
	public $plot;

	/**
	 * @var string
	 */
	public $imdbnumber;

	/**
	 * @var object
	 */
	public $streamdetails;

	/**
	 * Returns the list of genres as a string
	 * @return string
	 */
	public function getGenreString()
	{
		return implode(' / ', $this->genre);
	}

	/**
	 * @return string the plot for the item, or "Not available" if it's missing
	 */
	public function getPlot()
	{
		return !empty($this->plot) ? $this->plot : Yii::t('Misc', 'Not available');
	}

	/**
	 * @return boolean whether or not the item has a rating
	 */
	public function hasRating()
	{
		return !!$this->rating;
	}

	/**
	 * @return string the rating for the item (formatted)
	 */
	public function getRating()
	{
		return number_format($this->rating, 1);
	}

	/**
	 * @return string the runtime string or null if runtime is not available
	 */
	public function getRuntimeString()
	{
		return $this->runtime ? (int)($this->runtime / 60).' min' : null;
	}

}
