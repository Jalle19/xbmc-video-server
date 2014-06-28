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
	 * @param array $htmlOptions options to pass to the control group
	 * @return string the HTML for the input
	 */
	public function typeaheadFieldControlGroup($model, $attribute, $data, $htmlOptions = array())
	{
		// Generate a unique ID for this element
		CHtml::resolveNameID($model, $attribute, $htmlOptions);
		TbHtml::addCssClass('twitter-typeahead-input', $htmlOptions);
		$id = $htmlOptions['id'];
		
		Yii::app()->clientScript->registerScript($id, "
			$('#{$id}').typeahead({name: '{$id}',local: {$data},limit: 10});
		", CClientScript::POS_READY);

		return $this->textFieldControlGroup($model, $attribute, $htmlOptions);
	}

}