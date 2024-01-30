<?php

namespace cwc24\mqtt\Demo2\Base;

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
