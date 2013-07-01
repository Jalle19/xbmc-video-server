<?php

/**
 * Represents the currently authenticated user
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class WebUser extends CWebUser
{

	/**
	 * @var User the user model
	 */
	private $_model;

	/**
	 * Returns the user's role
	 * @return string
	 */
	public function getRole()
	{
		$user = $this->loadUser();

		return $user !== null ? $user->role : User::ROLE_NONE;
	}

	/**
	 * Loads the user model
	 * @return User the user
	 */
	protected function loadUser()
	{
		if ($this->_model === null)
		{
			if ($this->id !== null)
				$this->_model = User::model()->findByPk($this->id);
		}

		return $this->_model;
	}

}
