<?php

/**
 * Form model for the movie filter
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class MovieFilterForm extends CFormModel
{

	const GENRE_TYPE_MOVIE = 'movie';
	const SORT_ORDER_ASCENDING = 'ascending';

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
	 * @var array list of all genres (key same as value)
	 */
	private $_genres;

	/**
	 * Initializes the model. The genre list is populated here.
	 */
	public function init()
	{
		$response = Yii::app()->xbmc->performRequest('VideoLibrary.GetGenres', array(
			'type'=>self::GENRE_TYPE_MOVIE,
			'sort'=>array('order'=>self::SORT_ORDER_ASCENDING, 'method'=>'label')));

		foreach ($response->result->genres as $genre)
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

}