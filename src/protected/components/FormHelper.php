<?php

/**
 * Contains common helper functions needed for forms
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class FormHelper
{

	/**
	 * Genereates a cancel button that links to the specified URL
	 * @param mixed $url the button URL
	 * @return the HTML for the button
	 */
	public static function cancelButton($url)
	{
		return TbHtml::linkButton('Cancel', array(
			'url'=>$url,
			'color'=>TbHtml::BUTTON_COLOR_INFO,
			'class'=>'btn-padded',
		));
	}

	/**
	 * Generates a nicely formatted help block, useful to explain what a form or 
	 * a page does. Nothing is rendered if the "showHelpBlocks" setting is set 
	 * to false.
	 * @param string $content the block content
	 * @return string the HTML for the help block
	 */
	public static function helpBlock($content)
	{
		if (!Setting::getValue('showHelpBlocks'))
			return;
		
		$output  = CHtml::openTag('p', array('class'=>'form-help'));
		$output .= TbHtml::icon(TbHtml::ICON_EXCLAMATION_SIGN);
		$output .= $content;
		$output .= CHtml::closeTag('p');
		
		return $output;
	}

}