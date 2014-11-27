<?php

namespace langosh\rest\resource;

/**
 * @name IRestResourceContainer
 * @author Jan Svatoš <svatosja@gmail.com>
 */
interface IRestResourceContainer {
	
	/**
	 * @param mixed $id
	 * @return \langosh\rest\resource\RestResource
	 * @throws \langosh\rest\resource\RestResourceException
	 */
	public function getResource($id);
	
}
