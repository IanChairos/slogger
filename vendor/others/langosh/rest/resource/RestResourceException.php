<?php

namespace langosh\rest\resource;

/**
 * @name RestResourceException
 * @author Jan Svatoš <svatosja@gmail.com>
 */
class RestResourceException extends \Exception {
	
	static public function unknownMethod($method) {
		return new self('Unknown resource method ['.$method.']', 405);
	}
	
	static public function runtimeError(\Exception $e) {
		return new self('Resource runtime error: '.$e->getMessage(), (int)$e->getCode(), $e);
	}
	
	static public function unknownResource($id) {
		return new self('Unknown resource ['.$id.']', 404);
	}
	
	static public function missingRequiredData($name) {
		return new self('Missing required data ['.$name.']', 400);
	}
	
}
