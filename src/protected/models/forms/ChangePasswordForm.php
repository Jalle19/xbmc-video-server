<?php

/**
 * Form model for the change password form
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class ChangePasswordForm extends CFormModel
{

	/**
	 * @var string the user's current password
	 */
	public $currentPassword;

	/**
	 * @var string the new password
	 */
	public $newPassword;

	/**
	 * @var string the new password (repeated)
	 */
	public $newPasswordRepeat;

	/**
	 * @var User the user model
	 */
	private $_user;

	/**
	 * @return array the attribute labels for this model
	 */
	public function attributeLabels()
	{
		return array(
			'currentPassword'=>Yii::t('Password', 'Current password'),
			'newPassword'=>Yii::t('Password', 'New password'),
			'newPasswordRepeat'=>Yii::t('Password', 'New password (repeat)'),
		);
	}

	/**
	 * @return array the validation rules for this controller
	 */
	public function rules()
	{
		return array(
			array('currentPassword, newPassword, newPasswordRepeat', 'required'),
			array('currentPassword', 'checkCurrentPassword'),
			array('newPassword', 'compare', 'compareAttribute'=>'newPasswordRepeat'),
			array('newPassword', 'compare', 'compareAttribute'=>'currentPassword',
				'operator'=>'!=', 'message'=>Yii::t('Password', 'New password cannot be the same as the old one')),
		);
	}

	/**
	 * Loads the user model
	 * @return boolean whether validation should continue
	 */
	protected function beforeValidate()
	{
		$this->_user = User::model()->findCurrent();

		return parent::beforeValidate();
	}

	/**
	 * Checks that currentPassword matches the user's current password
	 * @param string $attribute the attribute being validated
	 */
	public function checkCurrentPassword($attribute)
	{
		if (!User::checkPassword($this->{$attribute}, $this->_user->password))
			$this->addError($attribute, Yii::t('Password', 'Incorrect password'));
	}

}
