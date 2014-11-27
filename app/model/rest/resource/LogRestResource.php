<?php

namespace App\Model\rest\resource;

use langosh\rest\resource\RestResource;
use langosh\rest\resource\RestResourceException;
use langosh\rest\server\HttpRestServer;
use App\Model\repository\ILogRepository;
use App\Model\entity\LogEntity;

/**
 * @name LogRestResource
 * @author Jan Svatoš <svatosja@gmail.com>
 */
class LogRestResource extends RestResource {
	
	/** @var \App\Model\repository\ILogRepository */
	private $logRepository;

	
	/**
	 * @param \App\Model\repository\ILogRepository $logRepository
	 */
	public function __construct(ILogRepository $logRepository) {
		$this->logRepository = $logRepository;
		$this->setMethodHandler(HttpRestServer::METHOD_POST, array($this,'createLog'));
	}

	
	/**
	 * @param int $id
	 * @throws \langosh\rest\resource\RestResourceException
	 */
	public function getResource($id) {
		throw RestResourceException::unknownResource($id);
	}
	
	
	/**
	 * @param array $data
	 */
	public function createLog($data) {
		$entity = new LogEntity();
		$entity
				->setService( $this->getRequiredVariable($data,'service') )
				->setPriority( $this->getRequiredVariable($data,'priority') )
				->setTitle( $this->getRequiredVariable($data,'title') )
				->setDescription( $this->getRequiredVariable($data,'message') )
				->setCreated( new \DateTime() );
		
		$this->logRepository->create($entity);
	}
	
	
	/**
	 * @param array $data
	 * @param string $key
	 * @return mixed
	 * @throws \langosh\rest\resource\RestResourceException
	 */
	private function getRequiredVariable($data, $key) {
		if( !\array_key_exists($key, $data) ) {
			throw RestResourceException::missingRequiredData($key);
		}
		return $data[$key];
	}
	
}
