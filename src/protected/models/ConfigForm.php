<?php

/**
 * Form model for the application settings. The property names here should 
 * match those of the configuration file.
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
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
		);
	}

}