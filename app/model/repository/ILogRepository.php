<?php

namespace App\Model\repository;

use App\Model\entity\LogEntity;

/**
 * @name ILogRepository
 * @author Jan Svatoš <svatosja@gmail.com>
 */
interface ILogRepository {
	
	/**
	 * @param LogEntity $log
	 * @return bool
	 */
	public function create( LogEntity $log );
	
	/**
	 * @param int $id
	 * @return LogEntity
	 */
	public function get($id);
	
	/**
	 * @return array
	 */
	public function getNewFatalErrors();
	
	/**
	 * @param string $priority
	 * @param \DateTime $dateTimeFrom
	 * @return array
	 */
	public function getNewRepeatingErrors($priority,\DateTime $dateTimeFrom, $minCount);
	
	/**
	 * @param array $hashToDateTime
	 * @throws \Exception
	 */
	public function markReportedMessages(array $hashToDateTime);
	
	/**
	 * @param int $limit
	 * @return LogEntity[]
	 */
	public function getLastErrors($limit);
	
	/**
	 * @param int $limit
	 * @return LogEntity[]
	 */
	public function getTopPriorityErrors($limit);
	
}
