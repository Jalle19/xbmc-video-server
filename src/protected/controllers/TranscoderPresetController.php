<?php

/**
 * Handles transcoder presets
 * 
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class TranscoderPresetController extends AdminOnlyController
{

	/**
	 * Creates a new preset
	 */
	public function actionCreate()
	{
		$model = new TranscoderPreset();

		if (isset($_POST['TranscoderPreset']))
		{
			$model->attributes = $_POST['TranscoderPreset'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Preset created successfully');
				$this->redirect('admin');
			}
		}

		$this->render('create', array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a preset
	 * @param int $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if (isset($_POST['TranscoderPreset']))
		{
			$model->attributes = $_POST['TranscoderPreset'];

			if ($model->save())
			{
				Yii::app()->user->setFlash('success', 'Preset updated successfully');
				$this->redirect(array('admin'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->render('admin', array(
			'dataProvider'=>new CActiveDataProvider('TranscoderPreset'),
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TranscoderPreset the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = TranscoderPreset::model()->findByPk($id);
		if ($model === null)
			throw new PageNotFoundException();
		return $model;
	}

}
