<?php

/**
 * Cache component solely for cached API calls. By extending cachePath, we can 
 * flush this cache using flush() without affecting the main application cache.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class ApiCallCache extends CFileCache
{

	public function init()
	{
		$this->cachePath = Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'apiCallCache';
		
		parent::init();
	}

}