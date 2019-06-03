<?php

/**
 * Handles user accounts
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @author Geoffrey Bonneville <geoffrey.bonneville@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class UserController extends ModelController
{

	/**
	 * @inheritdoc
	 */
	public function filters()
	{
		return array_merge(parent::filters(), array(
			'accessControl',
		));
	}
	
	/**
	 * @inheritdoc
	 */
	public function accessRules()
	{
		return array_merge(
			array(
				array('allow',
					'actions'=>array('changePassword'),
				),
				array('allow',
					// Allow logged in users to update their own information
					'actions'=>array('update'),
					'expression'=>function($webUser) {
						return isset($_GET['id']) && $_GET['id'] == $webUser->id;
					}
				),
				array('allow',
					// Administrators can do anything
					'expression'=>function() {
						return Yii::app()->user->role == User::ROLE_ADMIN;
					},
				),
				array('deny'),
			), parent::accessRules()
		);
	}

	/**
	 * Updates a password
	 */
	public function actionChangePassword()
	{
		$model = new ChangePasswordForm();

		if (isset($_POST['ChangePasswordForm']))
		{
			$model->attributes = $_POST['ChangePasswordForm'];

			if ($model->validate())
			{
				// Change the password
				$user = $this->loadModel(Yii::app()->user->id);
				$user->password = $model->newPassword;
				$user->save();

				// Log and inform
				$this->log('"%s" updated his/her password', Yii::app()->user->name);
				Yii::app()->user->setFlash('success', Yii::t('User', 'Password successfully changed'));

				$this->redirect(array('user/update', 'id'=>$user->id));
			}
		}

		$this->render('changePassword', array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new user
	 */
	public function actionCreate()
	{
		$model = new User();

		if ($this->saveFromPost($model))
		{
			$this->log('"%s" created user "%s"', Yii::app()->user->name, 
					$model->username);
			
			Yii::app()->user->setFlash('success', Yii::t('User', 'Created user {username}', 
					array('{username}'=>'<em>'.$model->username.'</em>')));

			$this->redirect(array('admin'));
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a user
	 * @param int $id the user ID
	 */
	public function actionUpdate($id)
	{
		/* @var User $model */
		$model = $this->loadModel($id);
		
		// Clear the password
		$password = $model->password;
		$model->password = '';
		
		if (isset($_POST['User']))
		{
			$model->attributes = $_POST['User'];

			// Don't touch the password, it should only be changed through the change password action
			if (empty($model->password))
			{
				$model->inhibitPasswordHash();
				$model->password   = $password;	
			}
			
			if ($model->save())
			{
				$this->log('"%s" updated user "%s"', Yii::app()->user->name,
					$model->username);

				Yii::app()->user->setFlash('success', Yii::t('User', 'Updated user {username}',
					['{username}' => '<em>' . $model->username . '</em>']));
				
				$this->refresh();
			}
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a user
	 * @param int $id the user ID
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$model->delete();
		
		$this->log('"%s" deleted user "%s"', Yii::app()->user->name, 
						$model->username);

		$this->redirectOnDelete();
	}

}
