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

	/**
	 * @var string the movie title
	 */
	public $name;

	/**
	 * @var string the movie genre
	 */
	public $genre;

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
			'name'=>'Name',
			'genre'=>'Genre',
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
		);
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
	 * Returns a filter object which can be used when quering for media using 
	 * the methods in VideoLibrary
	 * @return \stdClass
	 */
	public function getFilter()
	{
		$filters = new stdClass();

		foreach ($this->getFilterDefinitions() as $field=> $options)
		{
			if (empty($options['value']))
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
	 * Should populate and returns the list of genres
	 */
	abstract public function getGenres();

	/**
	 * Should return an array containing the individual filter definitions
	 */
	abstract public function getFilterDefinitions();
}