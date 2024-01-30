<?php
require_once 'vendor/autoload.php';

use cwc24\mqtt\Demo1\Connection;
use cwc24\mqtt\Demo1\Sender;
use sskaje\mqtt\Exception as MqttException;

try{
	$connection = new Connection(
		'localhost',
		1883,
		'FocusTestClient1',
		'user',
		'password'
	);
}
catch( MqttException $e ){
	print( 'Connection to MQTT service failed (' . $e->getMessage() . ')' . PHP_EOL );
	exit( 1 );
}

$sender	= new Sender( $connection );

try{
	$topic		= 'test';
	$message	= 'Microtime: ' . microtime( TRUE );
	if( 'cli' === php_sapi_name() && isset( $argv[1] ) )
		$message	= 'Custom: '.$argv[1];

	$sender->publish( $topic, $message );
}
catch( MqttException $e ){
	print( 'Error: ' . $e->getMessage() . PHP_EOL );
	exit( 2 );
}




