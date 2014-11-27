<?php

namespace langosh\database;

/**
 * @name PDOWrapper
 * @author Jan Svatoš <svatosja@gmail.com>
 */
class PDOWrapper {

	/** @var bool */
	private $debug = FALSE;

	/** @var \PDO */
	private $db;


	public function __construct(\PDO $db) {
		$this->db = $db;
	}

	
	static public function create( $dsn, $user, $password, array $options = array() ) {
		$options += array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");
		$pdo = new \PDO($dsn, $user, $password, $options);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		return new self($pdo);
	}
	
	
	/**
	 * @return \PDO
	 */
	public function getConnection() {
		return $this->db;
	}


	public function beginTransaction() {
		$this->db->setAttribute(\PDO::ATTR_AUTOCOMMIT, 0);
		$this->db->beginTransaction();
	}


	public function commitTransaction() {
		$this->db->commit();
		$this->db->setAttribute(\PDO::ATTR_AUTOCOMMIT, 1);
	}


	public function rollbackTransaction() {
		$this->db->rollBack();
	}


	/**
	 * @param string $into
	 * @param array $values
	 * @return bool
	 */
	public function sqlInsert($into, array $values) {
		$keys = array_keys($values);
		$keysString = implode(',', $keys);

		$valuesPlaceholders = str_repeat('?,', count($keys));
		$valuesPlaceholders = trim($valuesPlaceholders, ",");

		$sql = "INSERT INTO `$into` ($keysString) VALUES($valuesPlaceholders)";
		return (bool)$this->sqlExecute($sql, array_values($values))->rowCount();
	}


	/**
	 * @return int
	 */
	public function getLastInsertId() {
		return $this->db->lastInsertId();
	}


	/**
	 * @return array
	 */
	public function getLastErrorInfo() {
		return $this->db->errorInfo();
	}


	/**
	 *
	 * @param string $from
	 * @param string $keyName
	 * @param mixed $keyValue
	 * @return bool
	 */
	public function sqlDeleteOne($from, $keyName, $keyValue) {
		return $this->db
						->prepare("DELETE FROM $from WHERE $keyName = ? LIMIT 1")
						->execute(array($keyValue));
	}


	/**
	 *
	 * @param string $sql
	 * @param array $params
	 * @return \PDOStatement
	 */
	public function sqlExecute($sql, array $params = array()) {
		if($this->debug) {
			$sqlDebug = $sql;
			foreach($params as $paramValue) {
				if( is_array($paramValue) ){
				    ob_start();
				        debug_print_backtrace();
					$trace = ob_get_contents();
				    ob_end_clean();
					\file_put_contents(APP_DIR.'/log/PDOWrapper_array_values_passed.log',$trace.PHP_EOL.$sql.PHP_EOL.print_r($params,TRUE).PHP_EOL,FILE_APPEND);
				}
				if(is_null($paramValue))
					$paramValue = "NULL";
				$sqlDebug = preg_replace('/\?/', $paramValue, $sqlDebug, 1);
			}
			echo PHP_EOL.">> SQL >> ".$sqlDebug;
		}

		$statement = $this->db->prepare($sql);
		$statement->execute($params);
		return $statement;
	}


	/**
	 *
	 * @param string $what
	 * @param string $from
	 * @param string $where
	 * @param array $params
	 * @return array
	 */
	public function sqlFetch($what, $from, $where, array $params = array()) {
		$statement = $this->sqlSelect($what, $from, $where, $params);
		return $statement->fetchAll(\PDO::FETCH_OBJ);
	}


	/**
	 *
	 * @param string $what
	 * @param string $from
	 * @param string $where
	 * @param array $params
	 * @return stdClass|bool
	 */
	public function sqlFetchOne($what, $from, $where, array $params = array()) {
		$statement = $this->sqlSelect($what, $from, $where, $params);
		return $statement->fetch(\PDO::FETCH_OBJ);
	}
	
	
	/**
	 *
	 * @param string $what
	 * @param string $from
	 * @param string $where
	 * @param array $params
	 * @return mixed
	 */
	public function sqlFetchValue($what, $from, $where, array $params = array()) {
		$statement = $this->sqlSelect($what, $from, $where, $params);
		return $statement->fetchColumn();
	}


	/**
	 *
	 * @param string $sql
	 * @param array $params
	 * @return array
	 */
	public function sqlFetchBySql($sql, array $params = array()) {
		return $this->sqlExecute($sql, $params)->fetchAll(\PDO::FETCH_OBJ);
	}


	/**
	 *
	 * @param string $sql
	 * @param array $params
	 * @return stdClass|bool
	 */
	public function sqlFetchOneBySql($sql, array $params = array()) {
		return $this->sqlExecute($sql, $params)->fetch(\PDO::FETCH_OBJ);
	}


	/**
	 *
	 * @param type $what
	 * @param type $from
	 * @param type $where
	 * @param array $params
	 * @return \PDOStatement
	 */
	public function sqlSelect($what, $from, $where, array $params = array()) {
		$sql = "SELECT $what FROM $from WHERE $where";
		return $this->sqlExecute($sql, $params);
	}


	/**
	 *
	 * @param strung $from
	 * @param string $where
	 * @param array $params
	 * @param string $what
	 * @return int
	 */
	public function sqlDelete($from, $where, $params = array(), $what = '') {
		$sql = "DELETE $what FROM $from WHERE $where";
		return $this->sqlExecute($sql, array_values($params))->rowCount();
	}


	/**
	 *
	 * @param string $table
	 * @param string $where
	 * @param array $params
	 * @return int
	 */
	public function sqlUpdate($table, $where, array $params) {
		$sql = "UPDATE $table SET ";
		foreach(array_keys($params) as $key) {
			$sql .= "$key=?,";
		}
		$sql = trim($sql, ",");
		$sql .= " WHERE $where";

		return $this->sqlExecute($sql, array_values($params))->rowCount();
		// @TODO co je spravne?
		return $this->sqlExecute($sql, array_values($params))->errorCode() === '00000';
	}


	public function enableDebug() {
		$this->debug = TRUE;
	}


	public function disableDebug() {
		$this->debug = FALSE;
	}
}
