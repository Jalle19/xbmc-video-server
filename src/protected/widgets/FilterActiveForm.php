<?php

Yii::import('bootstrap.widgets.TbActiveForm');

/**
 * Sub-class of TbActiveForm which provides a method for generating input text 
 * fields with typeahead functionality
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class FilterActiveForm extends TbActiveForm
{

	/**
	 * Renders a text field with typeahead functionality based on the specified 
	 * data.
	 * @param CModel $model the model
	 * @param string $attribute the attribute name
	 * @param string $data JavaScript-encoded array containing the data for the 
	 * typeahead
	 * @return string the HTML for the input
	 */
	public function typeaheadFieldControlGroup($model, $attribute, $data)
	{
		$this->registerTypeahead();

		// Generate a unique ID for this element
		$id = CHtml::ID_PREFIX.CHtml::$count++;

		Yii::app()->clientScript->registerScript($id, "
			$('#{$id}').typeahead({name: '{$id}',local: {$data},limit: 10});
		", CClientScript::POS_READY);

		return $this->textFieldControlGroup($model, $attribute, array(
					'id'=>$id,
					'class'=>'twitter-typeahead-input'));
	}

	/**
	 * Register the typeahead script
	 */
	private function registerTypeahead()
	{
		$script = YII_DEBUG ? 'typeahead.js' : 'typeahead.min.js';
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl
				.'/js/twitter-typeahead/'.$script, CClientScript::POS_END);
	}

}