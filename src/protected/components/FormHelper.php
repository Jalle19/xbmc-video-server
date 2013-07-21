<?php

/**
 * Contains common helper functions needed for forms
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
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

}