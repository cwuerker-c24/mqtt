<?php

namespace cwc24\mqtt\Demo1;

use sskaje\mqtt\Debug as MqttDebug;

abstract class AbstractBroker
{
	protected Connection $connection;

	/**
	 *	@param		Connection		$connection
	 */
	public function __construct( Connection $connection )
	{
		$this->connection		= $connection;
	}

	/**
	 *	@return		bool|NULL
	 */
	public function isConnected(): ?bool
	{
		return $this->connection->isConnected();
	}
}
