<?php

/**
 * Throws a basic 400 error exception
 *
 * @author Sam Stenvall <neggelandia@gmail.com>
 * @copyright Copyright &copy; Sam Stenvall 2013-
 * @license https://www.gnu.org/licenses/gpl.html The GNU General Public License v3.0
 */
class InvalidRequestException extends CHttpException
{
	public function __construct()
	{
		parent::__construct(400, 'Invalid request');
	}
}
