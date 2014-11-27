<?php

$container = require __DIR__ . '/../../bootstrap.php';

$alertService = $container->getService('AlertService');
$alertService->checkLog();