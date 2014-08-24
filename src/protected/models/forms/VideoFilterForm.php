<?php

/**
 * Base class for filter forms
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class VideoFilterForm extends CFormModel
{
	
	const WATCHED_STATUS_WATCHED = 'watched';
	const WATCHED_STATUS_UNWATCHED = 'unwatched';

	/**
	 * @var string the movie title
	 */
	public $name;

	/**
	 * @var string the movie genre
	 */
	public $genre;
	
	/**
	 * @var string watched status
	 */
	public $watchedStatus;

	/**
	 * @var array list of all genres (key same as value)
	 */
	protected $_genres = array();

	/**
	 * @return array the attribute labels for this model
	 */
	public function attributeLabels()
	{
		return array(
			'name'=>Yii::t('FilterForm', 'Name'),
			'genre'=>Yii::t('FilterForm', 'Genre'),
			'watchedStatus'=>Yii::t('FilterForm', 'Watched status'),
		);
	}

	/**
	 * @return array the validation rules for this model
	 */
	public function rules()
	{
		return array(
			array('name', 'safe'),
			array('genre', 'in', 'range'=>$this->getGenres()),
			array('watchedStatus', 'in', 'range'=>array_keys(self::getWatchedStatuses())),
		);
	}
	
	/**
	 * Populates and returns the list of genres
	 * @return array
	 */
	public function getGenres()
	{
		if (empty($this->_genres))
		{
			$genres = VideoLibrary::getGenres($this->getGenreType());

			foreach ($genres as $genre)
				$this->_genres[$genre->label] = $genre->label;
		}

		return $this->_genres;
	}

	/**
	 * @return boolean whether the filter is empty or not
	 */
	public function isEmpty()
	{
		foreach ($this->attributes as $attribute)
			if ($attribute)
				return false;

		return true;
	}
	
	/**
	 * Returns the request parameters that represent the current filter.
	 * @return array the request parameters
	 */
	public function buildRequestParameters()
	{
		$modelClass = get_class($this);
		$parameters = array();

		if (isset($_GET[$modelClass]))
		{
			$this->attributes = $_GET[$modelClass];

			if (!$this->isEmpty() && $this->validate())
				$parameters['filter'] = $this->getFilter();
		}

		return $parameters;
	}

	/**
	 * Returns a filter object which can be used when quering for media using 
	 * the methods in VideoLibrary
	 * @return \stdClass
	 */
	public function getFilter()
	{
		$filters = new stdClass();

		foreach ($this->getFilterDefinitions() as $field=> $options)
		{
			// '0' is a valid value so we can't use empty()
			if ($options['value'] === '' || $options['value'] === null)
				continue;

			$filter = new stdClass();
			$filter->field = $field;
			$filter->operator = $options['operator'];
			$filter->value = $options['value'];

			$filters->and[] = $filter;
		}

		return $filters;
	}
	
	/**
	 * Returns a list of possible watched statuses. Used for validation and 
	 * population of dropdown lists.
	 * @return array
	 */
	public static function getWatchedStatuses()
	{
		return array(
			self::WATCHED_STATUS_WATCHED=>Yii::t('WatchedStatus', 'Watched'),
			self::WATCHED_STATUS_UNWATCHED=>Yii::t('WatchedStatus', 'Unwatched'),
		);
	}
	
	/**
	 * Returns the definitions for the common filters
	 * @return array the filter definitions
	 */
	public function getCommonFilterDefinitions()
	{
		$filter = array();

		// only do a partial match on the title
		$filter['title'] = array(
			'operator'=>'contains',
			'value'=>$this->name);

		$filter['genre'] = array(
			'operator'=>'is',
			'value'=>$this->genre);
		
		$filter['actor'] = array(
			'operator'=>'is',
			'value'=>$this->actor,
		);
		
		if ($this->watchedStatus)
		{
			switch ($this->watchedStatus)
			{
				case self::WATCHED_STATUS_WATCHED:
					$operator = 'greaterthan';
					break;
				case self::WATCHED_STATUS_UNWATCHED:
					$operator = 'is';
					break;
			}

			$filter['playcount'] = array(
				'operator'=>$operator,
				'value'=>'0',
			);
		}
		
		return $filter;
	}

	/**
	 * @return string the type of genres to fetch (movies, TV shows, etc.)
	 */
	abstract public function getGenreType();

	/**
	 * Should return an array containing the individual filter definitions
	 */
	abstract public function getFilterDefinitions();
}