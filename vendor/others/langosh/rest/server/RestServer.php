<?php

namespace langosh\rest\server;

use langosh\rest\resource\IRestResourceContainer;
use langosh\rest\resource\RestResource;
use langosh\rest\server\RestServerException;

/**
 * @name RestServer
 * @author Jan Svatoš <svatosja@gmail.com>
 */
abstract class RestServer implements IRestResourceContainer {
	
	
	public function run() {
		try {
			$resource = $this->getTargetResource();
			$result = $resource->handleMethod($this->getMethod(),$this->getData());
			$this->sendResponse($result);
		} catch (\Exception $e) {
			$this->sendError($e);
		}
	}
	
	
	/**
	 * @return RestResource
	 */
	protected function getTargetResource() {
		$resourcePath = $this->getFilteredResourcePath();
		$rootResourceId = \array_shift($resourcePath);
		$resource = $this->getResource($rootResourceId);
		
		foreach( $resourcePath as $resourceId ) {
			$resource = $resource->getResource($resourceId);
		}
		
		return $resource;
	}
	
	
	/**
	 * @return string Method type
	 */
	abstract protected function getMethod();
	
	
	/**
	 * @return array Path to target resource
	 */
	abstract protected function getResourcePath();
	
	
	/**
	 * @return mixed Request data
	 */
	abstract protected function getData();
	
	
	/**
	 * @param mixed $data
	 */
	abstract protected function sendResponse($data);
	
	
	/**
	 * @param \Exception $exception
	 */
	abstract protected function sendError(\Exception $e);

	
	/**
	 * @return array
	 * @throws RestServerException
	 */
	protected function getFilteredResourcePath() {
		$resourcePath = array_filter($this->getResourcePath(), function($item){
			// we want to be able to get resource id = 0 ...
			if( is_null($item) || $item === FALSE ) {
				return FALSE;
			}
			return TRUE;
		});
		
		if( empty($resourcePath) ) {
			throw RestServerException::invalidResourcePath();
		}
				
		return $resourcePath;
	}
	
}
