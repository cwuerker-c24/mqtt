<?php

namespace cwc24\mqtt\Demo2\Base;

use RuntimeException;

class Sender extends AbstractBroker
{
	public function publish( string $topic, string $message ): array
	{
		if( NULL === $this->isConnected() )
			$this->connection->open();
		if( FALSE === $this->isConnected() )
			throw new RuntimeException( 'Connection not established' );
		return $this->connection->client->publish_async( $topic, $message, 0, 0 );
	}
}
