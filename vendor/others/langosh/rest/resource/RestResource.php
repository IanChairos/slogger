<?php

namespace langosh\rest\resource;

/**
 * @name RestResource
 * @author Jan Svatoš <svatosja@gmail.com>
 */
abstract class RestResource implements IRestResourceContainer {
	
	/** @var array */
	private $methodHandlers = array();
	
	
	/**
	 * @param mixed $method
	 * @param mixed $data
	 * @throws RestResourceException
	 */
	public function handleMethod($method, $data) {
		if( !array_key_exists($method, $this->methodHandlers) ) {
			throw RestResourceException::unknownMethod($method);
		}
		
		try {
			$result = \call_user_func($this->methodHandlers[$method], $data);
		} catch (\Exception $e) {
			throw RestResourceException::runtimeError($e);
		}
		
		return $result;
	}

	
	/**
	 * @param mixed $method
	 * @param \callable $callback
	 * @return \langosh\rest\resource\RestResource
	 */
	protected function setMethodHandler($method, $callback) {
		$this->methodHandlers[$method] = $callback;
		return $this;
	}
	
}
