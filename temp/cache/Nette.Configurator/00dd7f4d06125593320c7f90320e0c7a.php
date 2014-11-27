<?php
// source: /data/projects/other/skrz_logger/app/config/config.neon 
// source: /data/projects/other/skrz_logger/app/config/config.local.neon 

/**
 * @property App\Model\AlertService $AlertService
 * @property langosh\database\PDOWrapper $Database
 * @property App\Model\repository\MysqlLogRepository $LogRepository
 * @property App\Model\rest\resource\LogRestResource $LogResource
 * @property App\Model\rest\server\LogRestServer $LogServer
 * @property Nette\Mail\SendmailMailer $Mailer
 * @property Nette\Application\Application $application
 * @property Nette\Caching\Storages\FileStorage $cacheStorage
 * @property Nette\DI\Container $container
 * @property Nette\Http\Request $httpRequest
 * @property Nette\Http\Response $httpResponse
 * @property Nette\Bridges\Framework\NetteAccessor $nette
 * @property Nette\Application\IRouter $router
 * @property Nette\Http\Session $session
 * @property Nette\Security\User $user
 */
class SystemContainer extends Nette\DI\Container
{

	protected $meta = array(
		'types' => array(
			'nette\\object' => array(
				'nette',
				'nette.cacheJournal',
				'cacheStorage',
				'nette.httpRequestFactory',
				'httpRequest',
				'httpResponse',
				'nette.httpContext',
				'session',
				'nette.userStorage',
				'user',
				'application',
				'nette.presenterFactory',
				'nette.mailer',
				'nette.templateFactory',
				'Mailer',
				'container',
			),
			'nette\\bridges\\framework\\netteaccessor' => array('nette'),
			'nette\\caching\\storages\\ijournal' => array('nette.cacheJournal'),
			'nette\\caching\\storages\\filejournal' => array('nette.cacheJournal'),
			'nette\\caching\\istorage' => array('cacheStorage'),
			'nette\\caching\\storages\\filestorage' => array('cacheStorage'),
			'nette\\http\\requestfactory' => array('nette.httpRequestFactory'),
			'nette\\http\\irequest' => array('httpRequest'),
			'nette\\http\\request' => array('httpRequest'),
			'nette\\http\\iresponse' => array('httpResponse'),
			'nette\\http\\response' => array('httpResponse'),
			'nette\\http\\context' => array('nette.httpContext'),
			'nette\\http\\session' => array('session'),
			'nette\\security\\iuserstorage' => array('nette.userStorage'),
			'nette\\http\\userstorage' => array('nette.userStorage'),
			'nette\\security\\user' => array('user'),
			'nette\\application\\application' => array('application'),
			'nette\\application\\ipresenterfactory' => array('nette.presenterFactory'),
			'nette\\application\\presenterfactory' => array('nette.presenterFactory'),
			'nette\\application\\irouter' => array('router'),
			'nette\\mail\\imailer' => array('nette.mailer', 'Mailer'),
			'nette\\mail\\sendmailmailer' => array('nette.mailer', 'Mailer'),
			'nette\\bridges\\applicationlatte\\ilattefactory' => array('nette.latteFactory'),
			'nette\\application\\ui\\itemplatefactory' => array('nette.templateFactory'),
			'nette\\bridges\\applicationlatte\\templatefactory' => array('nette.templateFactory'),
			'app\\adminmodule\\routerfactory' => array('20_App_AdminModule_RouterFactory'),
			'nette\\security\\iauthenticator' => array('21_App_Model_UserManager'),
			'app\\model\\usermanager' => array('21_App_Model_UserManager'),
			'app\\model\\alertservice' => array('AlertService'),
			'app\\model\\repository\\ilogrepository' => array('LogRepository'),
			'app\\model\\repository\\mysqllogrepository' => array('LogRepository'),
			'langosh\\rest\\resource\\restresource' => array('LogResource'),
			'langosh\\rest\\resource\\irestresourcecontainer' => array('LogResource', 'LogServer'),
			'app\\model\\rest\\resource\\logrestresource' => array('LogResource'),
			'langosh\\rest\\server\\httprestserver' => array('LogServer'),
			'langosh\\rest\\server\\restserver' => array('LogServer'),
			'app\\model\\rest\\server\\logrestserver' => array('LogServer'),
			'langosh\\database\\pdowrapper' => array('Database'),
			'nette\\di\\container' => array('container'),
		),
	);


	public function __construct()
	{
		parent::__construct(array(
			'appDir' => '/data/projects/other/skrz_logger/app',
			'wwwDir' => '/data/projects/other/skrz_logger/app/SystemModule/api',
			'debugMode' => TRUE,
			'productionMode' => FALSE,
			'environment' => 'development',
			'consoleMode' => FALSE,
			'container' => array(
				'class' => 'SystemContainer',
				'parent' => 'Nette\\DI\\Container',
				'accessors' => TRUE,
			),
			'tempDir' => '/data/projects/other/skrz_logger/app/../temp',
			'database' => array(
				'driver' => 'mysql',
				'host' => 'localhost',
				'port' => 3306,
				'databaseName' => 'skrz_logger',
				'user' => 'root',
				'password' => 'kolikol',
			),
			'alerts' => array(
				'emailFrom' => 'root@localhost',
				'nameFrom' => 'Logger Server 1',
				'emailTo' => 'root@localhost',
				'priority' => array(
					'medium' => array('timeSpan' => 15, 'count' => 20),
					'low' => array('timeSpan' => 30, 'count' => 100),
				),
			),
		));
	}


	/**
	 * @return App\AdminModule\RouterFactory
	 */
	public function createService__20_App_AdminModule_RouterFactory()
	{
		$service = new App\AdminModule\RouterFactory;
		return $service;
	}


	/**
	 * @return App\Model\UserManager
	 */
	public function createService__21_App_Model_UserManager()
	{
		$service = new App\Model\UserManager($this->getService('Database'));
		return $service;
	}


	/**
	 * @return App\Model\AlertService
	 */
	public function createService__AlertService()
	{
		$service = new App\Model\AlertService($this->getService('LogRepository'), $this->getService('Mailer'), array(
			'emailFrom' => 'root@localhost',
			'nameFrom' => 'Logger Server 1',
			'emailTo' => 'root@localhost',
			'priority' => array(
				'medium' => array('timeSpan' => 15, 'count' => 20),
				'low' => array('timeSpan' => 30, 'count' => 100),
			),
		));
		return $service;
	}


	/**
	 * @return langosh\database\PDOWrapper
	 */
	public function createService__Database()
	{
		$service = langosh\database\PDOWrapper::create('mysql:host=localhost;dbname=skrz_logger', 'root', 'kolikol');
		if (!$service instanceof langosh\database\PDOWrapper) {
			throw new Nette\UnexpectedValueException('Unable to create service \'Database\', value returned by factory is not langosh\\database\\PDOWrapper type.');
		}
		return $service;
	}


	/**
	 * @return App\Model\repository\MysqlLogRepository
	 */
	public function createService__LogRepository()
	{
		$service = new App\Model\repository\MysqlLogRepository($this->getService('Database'), 'errors');
		return $service;
	}


	/**
	 * @return App\Model\rest\resource\LogRestResource
	 */
	public function createService__LogResource()
	{
		$service = new App\Model\rest\resource\LogRestResource($this->getService('LogRepository'));
		return $service;
	}


	/**
	 * @return App\Model\rest\server\LogRestServer
	 */
	public function createService__LogServer()
	{
		$service = new App\Model\rest\server\LogRestServer($this->getService('LogResource'));
		return $service;
	}


	/**
	 * @return Nette\Mail\SendmailMailer
	 */
	public function createService__Mailer()
	{
		$service = new Nette\Mail\SendmailMailer;
		return $service;
	}


	/**
	 * @return Nette\Application\Application
	 */
	public function createServiceApplication()
	{
		$service = new Nette\Application\Application($this->getService('nette.presenterFactory'), $this->getService('router'), $this->getService('httpRequest'), $this->getService('httpResponse'));
		$service->catchExceptions = FALSE;
		$service->errorPresenter = 'Error';
		Nette\Bridges\ApplicationTracy\RoutingPanel::initializePanel($service);
		Tracy\Debugger::getBar()->addPanel(new Nette\Bridges\ApplicationTracy\RoutingPanel($this->getService('router'), $this->getService('httpRequest'), $this->getService('nette.presenterFactory')));
		return $service;
	}


	/**
	 * @return Nette\Caching\Storages\FileStorage
	 */
	public function createServiceCacheStorage()
	{
		$service = new Nette\Caching\Storages\FileStorage('/data/projects/other/skrz_logger/app/../temp/cache', $this->getService('nette.cacheJournal'));
		return $service;
	}


	/**
	 * @return Nette\DI\Container
	 */
	public function createServiceContainer()
	{
		return $this;
	}


	/**
	 * @return Nette\Http\Request
	 */
	public function createServiceHttpRequest()
	{
		$service = $this->getService('nette.httpRequestFactory')->createHttpRequest();
		if (!$service instanceof Nette\Http\Request) {
			throw new Nette\UnexpectedValueException('Unable to create service \'httpRequest\', value returned by factory is not Nette\\Http\\Request type.');
		}
		return $service;
	}


	/**
	 * @return Nette\Http\Response
	 */
	public function createServiceHttpResponse()
	{
		$service = new Nette\Http\Response;
		return $service;
	}


	/**
	 * @return Nette\Bridges\Framework\NetteAccessor
	 */
	public function createServiceNette()
	{
		$service = new Nette\Bridges\Framework\NetteAccessor($this);
		return $service;
	}


	/**
	 * @return Nette\Caching\Cache
	 */
	public function createServiceNette__cache($namespace = NULL)
	{
		$service = new Nette\Caching\Cache($this->getService('cacheStorage'), $namespace);
		trigger_error('Service cache is deprecated.', 16384);
		return $service;
	}


	/**
	 * @return Nette\Caching\Storages\FileJournal
	 */
	public function createServiceNette__cacheJournal()
	{
		$service = new Nette\Caching\Storages\FileJournal('/data/projects/other/skrz_logger/app/../temp');
		return $service;
	}


	/**
	 * @return Nette\Http\Context
	 */
	public function createServiceNette__httpContext()
	{
		$service = new Nette\Http\Context($this->getService('httpRequest'), $this->getService('httpResponse'));
		return $service;
	}


	/**
	 * @return Nette\Http\RequestFactory
	 */
	public function createServiceNette__httpRequestFactory()
	{
		$service = new Nette\Http\RequestFactory;
		$service->setProxy(array());
		return $service;
	}


	/**
	 * @return Latte\Engine
	 */
	public function createServiceNette__latte()
	{
		$service = new Latte\Engine;
		$service->setTempDirectory('/data/projects/other/skrz_logger/app/../temp/cache/latte');
		$service->setAutoRefresh(TRUE);
		$service->setContentType('html');
		return $service;
	}


	/**
	 * @return Nette\Bridges\ApplicationLatte\ILatteFactory
	 */
	public function createServiceNette__latteFactory()
	{
		return new SystemContainer_Nette_Bridges_ApplicationLatte_ILatteFactoryImpl_nette_latteFactory($this);
	}


	/**
	 * @return Nette\Mail\SendmailMailer
	 */
	public function createServiceNette__mailer()
	{
		$service = new Nette\Mail\SendmailMailer;
		return $service;
	}


	/**
	 * @return Nette\Application\PresenterFactory
	 */
	public function createServiceNette__presenterFactory()
	{
		$service = new Nette\Application\PresenterFactory('/data/projects/other/skrz_logger/app', $this);
		$service->setMapping(array(
			'*' => 'App\\*Module\\Presenters\\*Presenter',
		));
		return $service;
	}


	/**
	 * @return Nette\Templating\FileTemplate
	 */
	public function createServiceNette__template()
	{
		$service = new Nette\Templating\FileTemplate;
		$service->registerFilter($this->getService('nette.latteFactory')->create());
		$service->registerHelperLoader('Nette\\Templating\\Helpers::loader');
		return $service;
	}


	/**
	 * @return Nette\Caching\Storages\PhpFileStorage
	 */
	public function createServiceNette__templateCacheStorage()
	{
		$service = new Nette\Caching\Storages\PhpFileStorage('/data/projects/other/skrz_logger/app/../temp/cache', $this->getService('nette.cacheJournal'));
		trigger_error('Service templateCacheStorage is deprecated.', 16384);
		return $service;
	}


	/**
	 * @return Nette\Bridges\ApplicationLatte\TemplateFactory
	 */
	public function createServiceNette__templateFactory()
	{
		$service = new Nette\Bridges\ApplicationLatte\TemplateFactory($this->getService('nette.latteFactory'), $this->getService('httpRequest'), $this->getService('httpResponse'), $this->getService('user'), $this->getService('cacheStorage'));
		return $service;
	}


	/**
	 * @return Nette\Http\UserStorage
	 */
	public function createServiceNette__userStorage()
	{
		$service = new Nette\Http\UserStorage($this->getService('session'));
		return $service;
	}


	/**
	 * @return Nette\Application\IRouter
	 */
	public function createServiceRouter()
	{
		$service = $this->getService('20_App_AdminModule_RouterFactory')->createRouter();
		if (!$service instanceof Nette\Application\IRouter) {
			throw new Nette\UnexpectedValueException('Unable to create service \'router\', value returned by factory is not Nette\\Application\\IRouter type.');
		}
		return $service;
	}


	/**
	 * @return Nette\Http\Session
	 */
	public function createServiceSession()
	{
		$service = new Nette\Http\Session($this->getService('httpRequest'), $this->getService('httpResponse'));
		$service->setExpiration('14 days');
		return $service;
	}


	/**
	 * @return Nette\Security\User
	 */
	public function createServiceUser()
	{
		$service = new Nette\Security\User($this->getService('nette.userStorage'), $this->getService('21_App_Model_UserManager'));
		Tracy\Debugger::getBar()->addPanel(new Nette\Bridges\SecurityTracy\UserPanel($service));
		return $service;
	}


	public function initialize()
	{
		date_default_timezone_set('Europe/Prague');
		Nette\Bridges\Framework\TracyBridge::initialize();
		Nette\Caching\Storages\FileStorage::$useDirectories = TRUE;
		$this->getByType("Nette\Http\Session")->exists() && $this->getByType("Nette\Http\Session")->start();
		header('X-Frame-Options: SAMEORIGIN');
		header('X-Powered-By: Nette Framework');
		header('Content-Type: text/html; charset=utf-8');
		Nette\Utils\SafeStream::register();
		Nette\Reflection\AnnotationsParser::setCacheStorage($this->getByType("Nette\Caching\IStorage"));
		Nette\Reflection\AnnotationsParser::$autoRefresh = TRUE;
	}

}



final class SystemContainer_Nette_Bridges_ApplicationLatte_ILatteFactoryImpl_nette_latteFactory implements Nette\Bridges\ApplicationLatte\ILatteFactory
{

	private $container;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	public function create()
	{
		$service = new Latte\Engine;
		$service->setTempDirectory('/data/projects/other/skrz_logger/app/../temp/cache/latte');
		$service->setAutoRefresh(TRUE);
		$service->setContentType('html');
		return $service;
	}

}
