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
	 * @param array $htmlOptions options to pass to the control group
	 * @return string the HTML for the input
	 */
	public function typeaheadFieldControlGroup($model, $attribute, $htmlOptions = array())
	{
		// Generate a unique ID for this element
		CHtml::resolveNameID($model, $attribute, $htmlOptions);
		$id = $htmlOptions['id'];
		
		Yii::app()->clientScript->registerScript($id, 
				$this->getTypeaheadScript($id, $htmlOptions), 
				CClientScript::POS_READY);

		return $this->textFieldControlGroup($model, $attribute, $htmlOptions);
	}
	
	/**
	 * Generates and returns the actual JavaScript for the typeahead field
	 * @param string $id
	 * @param array $htmlOptions
	 * @return string the script code
	 */
	protected function getTypeaheadScript($id, $htmlOptions)
	{
		$prefetch = $htmlOptions['prefetch'];
		$sourceName = $id.'_source';
		
		return "
			var {$sourceName} = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.whitespace,
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				prefetch: {
					url: '{$prefetch}'
				}
			});
			
			$('#{$id}').typeahead({
				hint: true,
				highlight: true,
				minLength: 1
			},
			{
				name: '{$id}',
				source: {$sourceName}
			});
		";
	}

}