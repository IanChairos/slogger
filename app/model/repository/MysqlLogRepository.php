<?php

namespace App\Model\repository;

use langosh\database\PDOWrapper;
use \App\Model\entity\LogEntity;

/**
 * @name MysqlLogRepository
 * @author Jan Svatoš <svatosja@gmail.com>
 */
class MysqlLogRepository implements ILogRepository {
	
	/** @var PDOWrapper */
	private $database;
	
	/** @var string */
	private $tableName;
	
	
	/**
	 * @param PDOWrapper $database
	 * @param string $tableName
	 */
	public function __construct(PDOWrapper $database, $tableName) {
		$this->database = $database;
		$this->tableName = $tableName;
	}

	
	/**
	 * @param LogEntity $log
	 * @return bool
	 */
	public function create( LogEntity $log ) {
		$values = array(
			'service' => $log->getService(),
			'priority' => $log->getPriority(),
			'title' => $log->getTitle(),
			'message' => $log->getDescription(),
			'created' => $log->getCreated()->format('Y-m-d H:i:s'),
			'hash' => \md5($log->getService().$log->getPriority().$log->getTitle().$log->getDescription()),
		);
		
		return $this->database->sqlInsert($this->tableName, $values);
	}
	
	/**
	 * @param int $id
	 * @return LogEntity|NULL
	 */
	public function get($id) {
		$result = $this->database->sqlFetchOne('*', $this->tableName, 'id=?', array($id));
		if( !$result ) {
			return NULL;
		}
		return $this->mapEntity($result);
	}
	
	
	/**
	 * @param int $limit
	 * @return array
	 */
	public function getLastErrors($limit) {
		$limitString = $limit > 0 ? ' LIMIT '.$limit : '';
		$rows = $this->database->sqlFetch('*', $this->tableName, '1 ORDER BY created DESC'.$limitString);
		$mapped = array();
		foreach ($rows as $row) {
			$mapped[] = $this->mapEntity($row);
		}
		return $mapped;
	}
	
	
	/**
	 * @param int $limit
	 * @return array
	 */
	public function getTopPriorityErrors($limit) {
		$limitString = $limit > 0 ? ' LIMIT '.$limit : '';
		$rows = $this->database->sqlFetch('*', $this->tableName, '1 ORDER BY priority DESC, created DESC'.$limitString);
		$mapped = array();
		foreach ($rows as $row) {
			$mapped[] = $this->mapEntity($row);
		}
		return $mapped;
	}

	
	/**
	 * @param mixed $rawData
	 * @return LogEntity
	 */
	protected function mapEntity($rawData) {
		$entity = new LogEntity();
		$entity
				->setId( $rawData->id )
				->setService( $rawData->service )
				->setPriority( $rawData->priority )
				->setTitle( $rawData->title )
				->setDescription( $rawData->message )
				->setCreated( new \DateTime($rawData->created) )
				->setHash( $rawData->hash )
				->setReported( $rawData->reported );
		
		return $entity;
	}

	
	/**
	 * @return array
	 */
	public function getNewFatalErrors() {
		$rows= $this->database->sqlFetch('count(*) as cnt,id,service,priority,title,message,created,reported,`hash`', $this->tableName, 'priority = ? AND reported = 0 GROUP BY `hash`', array(LogEntity::PRIORITY_FATAL));
		$mapped = array();
		foreach ($rows as $row) {
			$mapped[] = array($row->cnt,$this->mapEntity($row));
		}
		return $mapped;
	}
	
	
	/**
	 * @param string $priority
	 * @param \DateTime $from
	 * @param int $minCount
	 * @return array
	 */
	public function getNewRepeatingErrors($priority, \DateTime $from, $minCount) {
		$this->database->enableDebug();
		$rows= $this->database->sqlFetch('count(*) AS cnt,id,service,priority,title,message,created,reported,`hash`', $this->tableName, 'priority = ? AND created > ? AND reported = 0 GROUP BY `hash` HAVING cnt > ?', array($priority,$from->format('Y-m-d H:i:s'),$minCount));
		$mapped = array();
		foreach ($rows as $row) {
			$mapped[] = array($row->cnt,$this->mapEntity($row));
		}
		return $mapped;
	}
	
	
	/**
	 * @param array $hashToDateTime
	 * @throws \Exception
	 */
	public function markReportedMessages(array $hashToDateTime) {
		$this->database->beginTransaction();
		try {
			foreach ($hashToDateTime as $hash => $dateTimeTo) {
				$this->database->sqlExecute( 'UPDATE '.$this->tableName.' SET reported=1 WHERE `hash`=? AND `created` < ?', array($hash,$dateTimeTo));
			}
			$this->database->commitTransaction();
		} catch (\Exception $exc) {
			$this->database->rollbackTransaction();
			throw $exc; // -> nette
		}
	}
	
	
	/**
	 * @deprecated Bylo by to pomale pri rostouci DB
	 * @param string $priority
	 * @param type $dateFrom
	 */
	public function getAlertStats($priority, $dateFrom) {
		$sql = <<<SQL
-- Jiz nareportovane musime brat az od casu jejich posledniho reportu
SELECT
count(e.id),e.`hash`
-- e.*
FROM `errors_reported` er INNER JOIN `errors` e ON (er.`hash` = e.`hash` AND e.`priority` = :priority) WHERE e.`created` > er.`reported` AND e.`created` > :createdFrom -- '2014-07-11 23:59:59'
UNION
-- Nereportovane se ridi pouze casovym oknem
SELECT
count(e.id),e.`hash`
-- e.*
FROM `errors` e LEFT JOIN `errors_reported` er ON (er.`hash` = e.`hash` AND e.`priority` = :priority) WHERE er.`hash` IS NULL AND e.created > :createdFrom -- '2014-07-11 23:59:59'
GROUP BY `hash`
SQL;
	}

}
