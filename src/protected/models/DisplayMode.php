<?php

/**
 * Represents a display mode. A display mode determines how something is 
 * rendered on a page. The style determines how it is displayed and the context 
 * determines where the style applies.
 * 
 * Each user has his own display mode persisted in the database (as long as he 
 * has changed it once, otherwise defaults are always used).
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 *
 * The followings are the available columns in table 'backends':
 * @property int $id
 * @property int $user_id
 * @property string $context
 * @property string $mode
 */
class DisplayMode extends CActiveRecord
{

	const MODE_GRID = 'grid';
	const MODE_LIST = 'list';
	const CONTEXT_RESULTS = 'results';
	const CONTEXT_SEASONS = 'seasons';

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return static the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'display_mode';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('user_id, mode, context', 'required'),
			array('mode', 'in', 'range'=>array(self::MODE_GRID, self::MODE_LIST)),
			array('context', 'in', 'range'=>array(self::CONTEXT_RESULTS, self::CONTEXT_SEASONS)),
		);
	}

	/**
	 * @return array the relations for this model
	 */
	public function relations()
	{
		return array(
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * Sets the user_id to the current user before validating
	 * @return whether validation should be executed
	 */
	protected function beforeValidate()
	{
		$this->user_id = Yii::app()->user->id;

		return parent::beforeValidate();
	}

	/**
	 * Returns the display mode for the specified context
	 * @param string $context the context
	 * @return DisplayMode the model, or null if no mode is defined
	 */
	public function findByContext($context)
	{
		return $this->findByAttributes(array(
					'user_id'=>Yii::app()->user->id,
					'context'=>$context));
	}

}
