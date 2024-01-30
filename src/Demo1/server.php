<?php
require_once 'vendor/autoload.php';

use cwc24\mqtt\Demo1\Connection;
use cwc24\mqtt\Demo1\Receiver;
use sskaje\mqtt\Exception as MqttException;
use sskaje\mqtt\Message\PUBLISH as MqttPublishMessage;
use sskaje\mqtt\MessageHandler as MqttMessageHandler;
use sskaje\mqtt\MQTT as MqttClient;

class MessageHandler extends MqttMessageHandler
{
	public function publish( MqttClient $mqtt, MqttPublishMessage $publishObject ): void
	{
		print(server . phpvsprintf("%s (ID:%d QoS:%d Dup:%s Topic:%s) \e[32m%s\e[0m", [
				date('Y-m-d H:i:s'),
				$publishObject->getMsgID(),
				$publishObject->getQoS(),
				$publishObject->getDup() ? 'Y' : 'N',
				$publishObject->getTopic(),
				$publishObject->getMessage()
			]) . PHP_EOL);
	}
}


try {
	$connection = new Connection(
		'localhost',
		1883,
		'FocusTest',
		'user',
		'password'
	);
} catch( MqttException $e ){
	print( 'Connection to MQTT service failed ('.$e->getMessage().')'.PHP_EOL );
	exit( 1 );
}

$receiver	= new Receiver( $connection );
$topics		= [
	'test'	=> 2,
];
try {
	$receiver->receive( $topics, new MessageHandler() );
} catch( MqttException|Exception $e ){
	print( 'Error: '.$e->getMessage().PHP_EOL );
	exit( 2 );
}



