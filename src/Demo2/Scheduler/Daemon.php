<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace cwc24\mqtt\Demo2\Scheduler;

use Exception;
use cwc24\mqtt\Demo2\Base\Connection;
use cwc24\mqtt\Demo2\Base\QueueConnector;
use cwc24\mqtt\Demo2\Base\Receiver;
use const PHP_EOL;

final class Daemon
{
	private Receiver $receiver;

	private WorkDispatcher $handler;

	private int $nrWorkers	= 2;

	private array $topics		= [
		'start'		=> 2,
		'work'		=> 2,
		'work._'	=> 2,
	];

	private ?Connection $queueConnection	= NULL;

	public function __construct( ?int $nrWorkers = NULL )
	{
		if( NULL !== $nrWorkers && $nrWorkers > 0 )
			$this->nrWorkers	= $nrWorkers;

		$clientId	= 'Service.Scheduler.Daemon';
		$connection	= new QueueConnector( $clientId );
		$this->queueConnection	= $connection->getConnection();

		$this->handler	= new WorkDispatcher( $this->queueConnection, $this->nrWorkers );
		$this->receiver	= new Receiver( $this->queueConnection );
	}

	public function run()
	{
		try{
			$this->receiver->receive( $this->topics, $this->handler );
		}
		catch( Exception $e ){
			print( 'Exception: '.$e->getMessage().'.'.PHP_EOL );
			print( 'Trace:'.PHP_EOL.$e->getTraceAsString().PHP_EOL.PHP_EOL );
			exit( 1 );
		}
	}
}
