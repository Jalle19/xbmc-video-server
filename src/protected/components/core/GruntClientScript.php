<?php

/**
 * Client script implemenation which works side by side with Grunt. It makes it 
 * possible to define a list of scripts and styles that are included in a 
 * compiled file. These files will not be registered separately.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class GruntClientScript extends CClientScript
{

	/**
	 * @var array list of bundled files
	 */
	public $bundledFiles = array();

	/**
	 * {@inheritDoc}
	 */
	public function registerScriptFile($url, $position = null, array $htmlOptions = array())
	{
		if ($this->isBundled($url))
			return $this;

		return parent::registerScriptFile($url, $position, $htmlOptions);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function registerCssFile($url, $media = '')
	{
		if ($this->isBundled($url))
			return $this;

		return parent::registerCssFile($url, $media);
	}

	/**
	 * Checks whether a particular URL should be registered or not
	 * @param string $url the asset URL
	 * @return boolean whether the item is bundled
	 */
	private function isBundled($url)
	{
		foreach ($this->bundledFiles as $name)
		{
			if (substr($url, -(strlen($name))) === $name)
				return true;
		}

		return false;
	}

}
