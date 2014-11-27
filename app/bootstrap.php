<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode(TRUE);  // debug mode MUST NOT be enabled on production server
define('LOG_DIR',__DIR__ . '/../log');
$configurator->enableDebugger(__DIR__ . '/../log');

define('TEMP_DIR',__DIR__ . '/../temp');
$configurator->setTempDirectory(TEMP_DIR);

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../vendor/others')
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

return $container;
