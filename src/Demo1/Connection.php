<?php

namespace cwc24\mqtt\Demo1;

use RuntimeException;
use sskaje\mqtt\Debug as MqttDebug;
use sskaje\mqtt\MQTT as MqttClient;

class Connection
{
	public ?MqttClient $client			= NULL;
	protected string $host;
	protected int $port;
	protected ?bool $isConnected		= NULL;

	public function __construct( string $host, int $port = NULL, ?string $clientId = NULL, ?string $username = NULL, ?string $password = NULL )
	{
		$this->host		= $host;
		$this->port		= $port;
		$serverUri		= 'tcp://'.$host.':'.$port;
		$this->client	= new MqttClient( $serverUri, $clientId );
		if( NULL !== $username && NULL !== $password )
			$this->setAuth( $username, $password );
	}

	public function open(): self
	{
		$this->isConnected		= FALSE !== $this->client->connect();
		if( !$this->isConnected )
			throw new RuntimeException( 'Not connected' );
		#$context = stream_context_create();
		#$mqtt->setSocketContext($context);
		return $this;
	}

	public function isConnected(): ?bool
	{
		return $this->isConnected;
	}

	public function setAuth( string $username, string $password ): self
	{
		if( $this->isConnected() )
			throw new RuntimeException( 'Already connected' );
		$this->client->setAuth( $username, $password );
		return $this;
	}
}
