<?php

namespace cwc24\mqtt\Demo2\Base;

use RuntimeException;
use sskaje\mqtt\Exception as MqttException;

class QueueConnector
{
	protected string		$clientId			= 'Service.*';

	protected Connection	$connection;

	public function __construct( ?string $clientId = NULL )
	{
		if( NULL !== $clientId )
			$this->clientId	= $clientId;
		$this->connect();
	}

	public function getConnection(): Connection
	{
		return $this->connection;
	}

	protected function connect(): void
	{
		$host		= 'localhost';
		$port		= 1883;
		$username	= 'user';
		$password	= 'password';
		try{
			$this->connection	= new Connection( $host, $port, $this->clientId );
			if( FALSE !== $username && FALSE !== $password )
				$this->connection->setAuth( $username, $password );
		}
		catch( MqttException $e ){
			throw new RuntimeException( 'Connecting queue with client ID "'.$this->clientId.'" failed: '.$e->getMessage(), 0, $e );
		}
	}
}