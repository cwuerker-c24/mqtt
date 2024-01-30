<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace cwc24\mqtt\Demo2\Scheduler;

use cwc24\mqtt\Demo2\Base\Connection;
use cwc24\mqtt\Demo2\Base\Sender;
use sskaje\mqtt\Message\PUBLISH as MqttPublishMessage;
use sskaje\mqtt\MessageHandler as MqttMessageHandler;
use sskaje\mqtt\MQTT as MqttClient;

class WorkDispatcher extends MqttMessageHandler
{
	private WorkerPool $workerPool;

	private Sender $sender;

	public function __construct( Connection $queueConnection, int $nrWorkers = 1 )
	{
		$this->workerPool		= new WorkerPool( $nrWorkers );
		$this->sender			= new Sender( $queueConnection );
	}

	public function publish( MqttClient $mqtt, MqttPublishMessage $publishObject ): void
	{
		switch( $publishObject->getTopic() ){
			case 'work._':
				$this->handleCommandOnWorkMetaChannel( $publishObject );
				break;
			case 'work':
				$this->handleCommandOnWorkChannel( $publishObject );
		}
	}

	private function handleCommandOnWorkChannel( MqttPublishMessage $publishObject ): void
	{
		$workerNr	= $this->workerPool->getNextFreeWorkerNumber() ?? $this->workerPool->getNextWorker();
		$this->sender->publish( 'work.'.$workerNr, $publishObject->getMessage() );
	}

	private function handleCommandOnWorkMetaChannel( MqttPublishMessage $publishObject ): void
	{
		[$workerNr, $workerStatus]	= explode( ' ', $publishObject->getMessage(), 2 );
		$obj	= json_decode( $workerStatus );
		$this->workerPool->setWorkerStatus( (int) $workerNr, (int) $obj->status );
	}
}