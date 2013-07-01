<?php

/**
 * Form model for the application settings. The property names here should 
 * match those of the configuration file.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class ConfigForm extends CFormModel
{

	/**
	 * @var string the hostname
	 */
	public $hostname;

	/**
	 * @var int the port
	 */
	public $port;

	/**
	 * @var string the username
	 */
	public $username;

	/**
	 * @var string the password
	 */
	public $password;
	
	/**
	 * @var string reverse proxy location
	 */
	public $proxyLocation;

	/**
	 * @return array the attribute labels for this model
	 */
	public function attributeLabels()
	{
		return array(
			'hostname'=>'Hostname',
			'port'=>'Port',
			'username'=>'Username',
			'password'=>'Password',
		);
	}

	/**
	 * @return array the validation rules for this model
	 */
	public function rules()
	{
		return array(
			array('hostname, port, username, password', 'required'),
			array('port', 'numerical', 'integerOnly'=>true),
			array('proxyLocation', 'safe'),
		);
	}

}