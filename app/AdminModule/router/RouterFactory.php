<?php

namespace App\AdminModule;

use	Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList('Admin');
		$router[] = new Route('<presenter>/<action>[/<id>]', 'Dashboard:default');
		return $router;
	}

}
