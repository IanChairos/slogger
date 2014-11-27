<?php

while( 1 ) {
	$services = array('mailing','rival','skrz','facebook');
	$priority = array('notice','low','medium','fatal');
	
	$pr = $priority[ \array_rand($priority,1) ];
	
	$values = array(
		'service' => $services[ \array_rand($services,1) ],
		'priority' => $pr,
		'title' => $pr. ' title',
		'message' => 'asdfs fsadf af sdf sa fdsfsd fsda fs fsd fsdfdsf sdf sdf sdfsdaf sd fdsf',
	);

	$curl = \curl_init('http://api.logger.local/log/');
	\curl_setopt($curl, \CURLOPT_RETURNTRANSFER, TRUE);
	\curl_setopt($curl, \CURLOPT_CUSTOMREQUEST, 'POST');
	\curl_setopt($curl, \CURLOPT_POSTFIELDS, $values );
	curl_setopt($curl, CURLOPT_VERBOSE, 1);
	curl_setopt($curl, CURLOPT_HEADER, 1);

	$response = \curl_exec($curl);

	$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);
	
	echo 'Msg sent'.PHP_EOL;
	usleep( mt_rand(10*1000, 1500*1000) );
}
