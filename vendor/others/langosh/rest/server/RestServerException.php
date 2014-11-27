<?php

namespace langosh\rest\server;

/**
 * @name RestServerException
 * @author Jan Svatoš <svatosja@gmail.com>
 */
class RestServerException extends \Exception {
	
	static public function invalidResourcePath() {
		return new self('Invalid resource path', 400);
	}

	static public function unknownMethod($method) {
		return new self('Unknown method ['.$method.']', 405);
	}

}
