<?php

/**
 * Contains common helper functions needed for forms
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 */
class FormHelper
{

	/**
	 * Renders a cancel button that links to the specified URL
	 * @param mixed $url the button URL
	 */
	public static function cancelButton($url)
	{
		echo TbHtml::linkButton('Cancel', array(
			'url'=>$url,
			'color'=>TbHtml::BUTTON_COLOR_INFO,
			'class'=>'btn-padded',
		));
	}

}