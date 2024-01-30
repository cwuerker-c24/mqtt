<?php

namespace cwc24\mqtt\Demo1;

use RuntimeException;
use sskaje\mqtt\MessageHandler as MqttMessageHandler;

class Receiver extends AbstractBroker
{
	public function receive( array $topics, MqttMessageHandler $handler ): void
	{
		if( NULL === $this->connection->isConnected() )
			$this->connection->open();
		if( FALSE === $this->connection->isConnected() )
			throw new RuntimeException( 'Connection not established' );
		$this->connection->client->subscribe( $topics );
//		$mqtt->unsubscribe( array_keys( $topics ) );
		$this->connection->client->setHandler( $handler );
		$this->connection->client->loop();
	}
}

