<?php

/**
 * Base class for migrations whose only purpose is to add a setting to the 
 * settings table. Implementations only have to implement the getName() and 
 * getDefaultValue() methods, the actual migration is handled here.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2014-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
abstract class AddSettingMigration extends CDbMigration
{

	/**
	 * @return string the name of the new setting
	 */
	abstract public function getName();

	/**
	 * @return string the default value of the new setting
	 */
	abstract public function getDefaultValue();

	/**
	 * Adds the setting with its default value to the settings table. Nothing 
	 * is done if the setting already exists.
	 * @return type
	 */
	public function up()
	{
		$name = $this->getName();

		$setting = Setting::model()->findByPk($name);
		if ($setting !== null)
			return;

		$this->insert('settings', array(
			'name'=>$name,
			'value'=>(string)$this->getDefaultValue()));
	}

	/**
	 * Removes the setting
	 */
	public function down()
	{
		$this->delete('settings', 'name = :name', array(
			':name'=>$this->getName()));
	}

}