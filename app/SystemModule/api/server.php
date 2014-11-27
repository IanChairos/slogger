<?php

$container = require __DIR__ . '/../../bootstrap.php';

$server = $container->getService('LogServer');
$server->run();
