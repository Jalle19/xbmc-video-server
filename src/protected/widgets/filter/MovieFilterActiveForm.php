<?php

/**
 * Customization of FilterActiveForm which provides functionality to generate 
 * the movie name typeahead field
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class MovieFilterActiveForm extends FilterActiveForm
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
	public function movieNameFieldControlGroup($model, $attribute, $htmlOptions = array())
	{
		// Generate a unique ID for this element
		CHtml::resolveNameID($model, $attribute, $htmlOptions);
		$id = $htmlOptions['id'];

		Yii::app()->clientScript->registerScript($id, 
				$this->getMovieNameTypeaheadScript($id, $htmlOptions), 
				CClientScript::POS_READY);

		return $this->textFieldControlGroup($model, $attribute, $htmlOptions);
	}

	/**
	 * Generates and returns the actual JavaScript for the movie name field
	 * @param string $id
	 * @param array $htmlOptions
	 * @return string the script code
	 */
	protected function getMovieNameTypeaheadScript($id, $htmlOptions)
	{
		$prefetch = $htmlOptions['prefetch'];
		$sourceName = $id.'_source';

		return "
			var {$sourceName} = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
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
				source: {$sourceName},
				display: 'label',
				templates: {
					suggestion: function(data) {
						var suggestion = [
							'<div class=\"movie-name-typeahead-suggestion clearfix\">',
							data.thumbnail,
							'<p>' + data.label + ' (' + data.year + ')<br />',
							'<span class=\"genre\">' + data.genre + '</span></p>',
							'</div>',
						].join('\\n');
						
						return suggestion;
					}
				}
			});
			
			$('#{$id}').bind('typeahead:render', function(ev, suggestion) {
				$('.lazy').unveil();
			});
		";
	}

}
