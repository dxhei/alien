<?php

namespace JNews\PAY_WRITER\Util\Paypal\Core;

use Throwable;

class Io_Exception extends \Exception {
	public function __construct( $message = '', $code = 0, Throwable $previous = null ) {
		parent::__construct( $message, $code, $previous );
	}
}
