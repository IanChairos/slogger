<?php

namespace App\Model\entity;

/**
 * DTO for Log message
 * @name LogEntity
 * @author Jan Svatoš <svatosja@gmail.com>
 */
class LogEntity {
	
	const PRIORITY_NOTICE = 'notice';
	const PRIORITY_LOW = 'low';
	const PRIORITY_MEDIUM = 'medium';
	const PRIORITY_FATAL = 'fatal';
	
	/** @var int */
	private $id;
	
	/** @var string */
	private $service;
	
	/** @var string */
	private $priority;
	
	/** @var string */
	private $title;
	
	/** @var string */
	private $description;

	/** @var \DateTime */
	private $created;
	
	/** @var string */
	private $hash;
	
	/** @var bool */
	private $reported;
	
	
	/** @return int */
	public function getId() {
		return $this->id;
	}

	/** @return string */
	public function getService() {
		return $this->service;
	}

	/** @return string */
	public function getPriority() {
		return $this->priority;
	}

	/** @return string */
	public function getTitle() {
		return $this->title;
	}

	/** @return string */
	public function getDescription() {
		return $this->description;
	}

	/** @return \DateTime */
	public function getCreated() {
		return $this->created;
	}

	/** @return string */
	public function getHash() {
		return $this->hash;
	}

	/** 
	 * @param int $id 
	 * @return \App\Model\entity\LogEntity
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/** 
	 * @param string $service 
	 * @return \App\Model\entity\LogEntity
	 */
	public function setService($service) {
		$this->service = $service;
		return $this;
	}

	/** 
	 * @param string $priority 
	 * @return \App\Model\entity\LogEntity
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
		return $this;
	}

	/** 
	 * @param string $title 
	 * @return \App\Model\entity\LogEntity
	 */
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	/**
	 * @param string $description
	 * @return \App\Model\entity\LogEntity
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/** 
	 * @param \DateTime $created 
	 * @return \App\Model\entity\LogEntity
	 */
	public function setCreated(\DateTime $created) {
		$this->created = $created;
		return $this;
	}

	/** 
	 * @param string $hash 
	 * @return \App\Model\entity\LogEntity
	 */
	public function setHash($hash) {
		$this->hash = $hash;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getReported() {
		return (bool)$this->reported;
	}

	/**
	 * @param bool $reported
	 * @return \App\Model\entity\LogEntity
	 */
	public function setReported($reported) {
		$this->reported = (bool)$reported;
		return $this;
	}
	
}
