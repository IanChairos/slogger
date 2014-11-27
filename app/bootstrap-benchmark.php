<?php
$useNette = 0;
$mysql =1;
$once = 1;

if( $useNette ) {
	require __DIR__ . '/../vendor/autoload.php';

	$configurator = new Nette\Configurator;

	//$configurator->setDebugMode(TRUE);  // debug mode MUST NOT be enabled on production server
	$configurator->enableDebugger(__DIR__ . '/../log');

	$configurator->setTempDirectory(__DIR__ . '/../temp');

	$configurator->createRobotLoader()
		->addDirectory(__DIR__)
		->addDirectory(__DIR__ . '/../vendor/others')
		->register();

	$configurator->addConfig(__DIR__ . '/config/config.neon');
	$configurator->addConfig(__DIR__ . '/config/config.local.neon');

	$container = $configurator->createContainer();
	\ini_set('display_errors', '1');\error_reporting(\E_ALL);
	$pdo = $container->getService('Database');
	
	\Tracy\Debugger::enable(TRUE);
	
	/* @var $pdo \App\Model\PDOWrapper */
} else {
	require_once __DIR__.'/model/PDOWrapper.php';
	require_once __DIR__.'/../vendor/nette/utils/src/Utils/Random.php';

	$pdo = \App\Model\PDOWrapper::create("mysql:host=localhost;dbname=skrz_logger", "root", "kolikol");
}

$services = array('mailing','rival','skrz','facebook');
$priority = array('notice','low','medium','fatal');

if( $once ) {
	$values = array(
		'service' => $services[ \array_rand($services,1) ],
		'priority' => $priority[ \array_rand($priority,1) ],
		'title' => 'sfasdfsfd',
		'message' => 'asdfs fsadf af sdf sa fdsfsd fsda fs fsd fsdfdsf sdf sdf sdfsdaf sd fdsf',
		'created' => date('Y-m-d H:i:s'),
	);
	
	if( $mysql ) {
		$pdo->sqlInsert('errors', $values);
	} else {
//		$uuidsJson = \file_get_contents('http://localhost:5984/_uuids');
//		$uuids = \json_decode($uuidsJson)->uuids;
//		$uuid = current($uuids);
		$uuid = \uniqid();
		$curl = \curl_init('http://localhost:5984/speed/'. $uuid);
		\curl_setopt($curl, \CURLOPT_RETURNTRANSFER, TRUE);
		\curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'PUT');
		\curl_setopt($curl, \CURLOPT_POSTFIELDS, \json_encode($values) );
		\curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-type: application/json',
		));
		\curl_exec($curl);

		$code = \curl_getinfo($curl,\CURLINFO_HTTP_CODE);
		if( $code != '201' ) {
			echo 'ERROR '.$code.PHP_EOL;
		}
	}
	exit;
}


$lastEffectiveness = 0;
$insertsTotal = 0;
$batch = 1000;
$inserts = 0;
$start = \microtime(true);
if( $mysql ) {
	$pdo->beginTransaction();
}
$couchDocs = array();
$topBatch = 0;
$topEffectiveness = 0;

while( TRUE ) {
	
	$values = array(
		'service' => $services[ \array_rand($services,1) ],
		'priority' => $priority[ \array_rand($priority,1) ],
		'title' => 'sfasdfsfd',
		'message' => 'asdfs fsadf af sdf sa fdsfsd fsda fs fsd fsdfdsf sdf sdf sdfsdaf sd fdsf',
//		'title' => \Nette\Utils\Random::generate(\rand(10, 150)),
//		'message' => \Nette\Utils\Random::generate(\rand(100, 10000)),
		'created' => date('Y-m-d H:i:s'),
	);
	
	if( $mysql ) {
		$pdo->sqlInsert('errors', $values);
	} else {
//		\sendCouch($values);
		$values['key'] = \time();
		$couchDocs[] = $values;
	}
	
	$insertsTotal++;
	$inserts++;
	if( $inserts >= $batch ) {
		if( $mysql ) {
			$pdo->commitTransaction();
		} else {
			\sendCouch($couchDocs);
			$couchDocs = array();
		}
		
		$delta = \microtime(true) - $start;
		echo "\r [top $topEffectiveness/sec, batch $topBatch] [current ". \round( $inserts / $delta , 1) . '/sec], '.$insertsTotal.' total, batch '.$batch;
		
		$currentEffectiveness = $inserts / $delta;
		
		if( $currentEffectiveness > $topEffectiveness ) {
			$topBatch = $batch;
			$topEffectiveness = $currentEffectiveness;
		}
		
		if( $lastEffectiveness < $currentEffectiveness ) {
//			$batch = round( $batch*1.05 );
//			$batch+=\rand(1, 10);
			$batch++;
		} else {
//			$batch = round( $batch*0.95 );
//			$batch-=\rand(1, 10);
			$batch--;
		}
		
		$inserts = 0;
		$lastEffectiveness = $currentEffectiveness;
		
		if( $mysql ) {
			$pdo->beginTransaction();
		}
		$start = \microtime(true);
	}
}
//echo 'ok';
function sendCouch($values) {
//	$uuidsJson = \file_get_contents('http://localhost:5984/_uuids');
//	$uuids = \json_decode($uuidsJson)->uuids;
//	$uuid = current($uuids);

//	$uuid = \uniqid();
	
	$curl = \curl_init('http://localhost:5984/speed/_bulk_docs');
//	$curl = \curl_init('http://localhost:5984/speed/_bulk_docs'.$uuid);
	\curl_setopt($curl, \CURLOPT_RETURNTRANSFER, TRUE);
	\curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'POST');
	\curl_setopt($curl, \CURLOPT_POSTFIELDS, \json_encode(array('docs'=>$values)) );
//	\curl_setopt($curl, \CURLOPT_POSTFIELDS, \json_encode($values) );
	\curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-type: application/json',
	));
	\curl_exec($curl);
	
	$code = \curl_getinfo($curl,\CURLINFO_HTTP_CODE);
	if( $code != '201' ) {
		echo 'ERROR '.$code.PHP_EOL;
	}
}