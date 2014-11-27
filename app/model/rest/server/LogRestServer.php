<?php

namespace App\Model\rest\server;

use langosh\rest\server\HttpRestServer;
use langosh\rest\resource\RestResourceException;
use App\Model\rest\resource\LogRestResource;

/**
 * @name LogRestServer
 * @author Jan Svatoš <svatosja@gmail.com>
 */
class LogRestServer extends HttpRestServer {
	
	/** @var LogRestResource */
	private $logResource;
	
	
	public function __construct(LogRestResource $logResource) {
		$this->logResource = $logResource;
	}

	
	public function getResource($id) {
		if( \strtolower($id) !== 'log' ) {
			throw RestResourceException::unknownResource($id);
		}
		return $this->logResource;
	}

}
