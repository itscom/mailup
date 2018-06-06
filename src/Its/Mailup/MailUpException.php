<?php
/**
 * Created by PhpStorm.
 * User: ngi
 * Date: 06/06/2018
 * Time: 12:11
 */

namespace Its\Mailup;

class MailUpException extends \RuntimeException {

	public function __construct($message, $code = 0, \Exception $previous = null) {
		// some code

		// make sure everything is assigned properly
		parent::__construct($message, $code, $previous);
	}
}