<?php

/**
 * Represents an image placeholder
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2015-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class PlaceholderThumbnail extends AbstractThumbnail
{

	public function getUrl()
	{
		return Yii::app()->baseUrl.'/'.$this->_path;
	}

}
