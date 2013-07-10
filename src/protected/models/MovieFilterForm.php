<?php

/**
 * Form model for the movie filter
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MovieFilterForm extends CFormModel
{

	const QUALITY_SD = 'sd';
	const QUALITY_720 = 720;
	const QUALITY_1080 = 1080;

	/**
	 * @var string the movie title
	 */
	public $name;

	/**
	 * @var string the movie genre
	 */
	public $genre;

	/**
	 * @var int the movie year
	 */
	public $year;
	
	/**
	 * @var string the video quality
	 */
	public $quality;

	/**
	 * @var array list of all genres (key same as value)
	 */
	private $_genres = array();

	/**
	 * Initializes the model. 
	 */
	public function init()
	{
		// Populate the genre list
		foreach (VideoLibrary::getGenres() as $genre)
			$this->_genres[$genre->label] = $genre->label;
	}

	/**
	 * @return array the attribute labels for this model
	 */
	public function attributeLabels()
	{
		return array(
			'name'=>'Name',
			'genre'=>'Genre',
			'year'=>'Year',
		);
	}

	/**
	 * @return array the validation rules for this model
	 */
	public function rules()
	{
		return array(
			array('name', 'safe'),
			array('genre', 'in', 'range'=>$this->_genres),
			array('year', 'numerical', 'integerOnly'=>true),
			array('quality', 'in', 'range'=>array_keys($this->getQualities())),
		);
	}

	/**
	 * Getter for $_genres;
	 * @return array
	 */
	public function getGenres()
	{
		return $this->_genres;
	}
	
	/**
	 * Returns the possible qualities
	 * @return array
	 */
	public function getQualities()
	{
		return array(
			self::QUALITY_SD=>'SD',
			self::QUALITY_720=>'720p',
			self::QUALITY_1080=>'1080p',
		);
	}

}