<?php

/**
 * Form model for the movie filter
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MovieFilterForm extends VideoFilterForm
{

	const QUALITY_SD = 'sd';
	const QUALITY_720 = 720;
	const QUALITY_1080 = 1080;

	/**
	 * @var int the movie year
	 */
	public $year;
	
	/**
	 * @var string the video quality
	 */
	public $quality;
	
	/**
	 * @var float rating of the movie
	 */
	public $rating;
	
	/**
	 * @var string the director of the movie
	 */
	public $director;
	
	public function getGenreType()
	{
		return VideoLibrary::GENRE_TYPE_MOVIE;
	}
	
	/**
	 * @return array the attribute labels for this model
	 */
	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'year'=>Yii::t('FilterForm', 'Year'),
			'quality'=>Yii::t('FilterForm', 'Quality'),
			'rating'=>Yii::t('FilterForm', 'Minimum rating'),
			'director'=>Yii::t('FilterForm', 'Director'),
		));
	}

	/**
	 * @return array the validation rules for this model
	 */
	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('year', 'numerical', 'integerOnly'=>true),
			array('quality', 'in', 'range'=>array_keys($this->getQualities())),
			array('rating', 'numerical', 'max'=>'10'),
			array('director', 'safe'),
		));
	}
	
	/**
	 * Pre-validation logic
	 * @return boolean whether to perform validation at all
	 */
	protected function beforeValidate()
	{
		// Convert rating to an actual double
		$this->rating = (double)str_replace(',', '.', $this->rating);

		return parent::beforeValidate();
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
	
	/**
	 * Returns the defined filter as an array
	 * @return array the filter
	 */
	public function getFilterDefinitions()
	{
		$filter = parent::getCommonFilterDefinitions();

		$filter['year'] = array(
			'operator'=>'is',
			'value'=>$this->year);
		
		$filter['rating'] = array(
			'operator'=>'greaterthan',
			'value'=>$this->rating);

		$filter['director'] = array(
			'operator'=>'is',
			'value'=>$this->director,
		);
		
		$quality = $this->quality;

		// SD means anything less than 720p
		if ($quality == self::QUALITY_SD)
		{
			$filter['videoresolution'] = array(
				'operator'=>'lessthan',
				'value'=>(string)self::QUALITY_720);
		}
		else
		{
			$filter['videoresolution'] = array(
				'operator'=>'is',
				'value'=>$quality);
		}

		return $filter;
	}

}
