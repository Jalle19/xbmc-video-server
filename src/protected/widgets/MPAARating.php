<?php

/**
 * Renders the MPAA rating for a media item
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MPAARating extends CWidget
{

	/**
	 * @var string the rating
	 */
	public $rating;

	/**
	 * Runs the widget
	 */
	public function run()
	{
		// MPAA rating is not always available
		if ($this->rating)
			echo '<p>MPAA rating: '.$this->rating.'</p>';
	}

}
