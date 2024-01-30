<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace cwc24\mqtt\Demo2\Worker;

use Exception;
use cwc24\mqtt\Demo2\Base\Connection;
use cwc24\mqtt\Demo2\Base\QueueConnector;
use cwc24\mqtt\Demo2\Base\Receiver;
use const PHP_EOL;

final class Daemon
{
	public const STATUS_UNKNOWN		= 0;
	public const STATUS_OFFLINE		= 1;
	public const STATUS_FREE		= 2;
	public const STATUS_OCCUPIED	= 3;

	private Receiver $receiver;

	private QueueCommandExecutor $handler;

	private Connection $queueConnection;

	private string $topic			= 'work.#nr';

	private int $nr					= 0;

	public function run()
	{
		$this->nr	= (int) ( $argv[1] ?? 1 );
		$this->initConnection();
		$this->initReceiver();
		print( "Started Worker #".$this->nr.PHP_EOL );
		$this->topic	= str_replace( '#nr', (string) $this->nr, $this->topic );

		try{
			$this->receiver->receive( [$this->topic => 2], $this->handler );
		}
		catch( Exception $e ){
			print( 'Exception: '.$e->getMessage().'.'.PHP_EOL );
			print( 'Trace:'.PHP_EOL.$e->getTraceAsString().PHP_EOL.PHP_EOL );
			exit( 1 );
		}
	}

	//  --  PRIVATE  --  //

	private function initConnection(): void
	{
		$clientId	= 'Service.Worker#'.$this->nr;
		$connection	= new QueueConnector( $clientId );
		$this->queueConnection	= $connection->getConnection();
	}

	private function initReceiver(): void
	{
		$this->receiver	= new Receiver( $this->queueConnection );
		$this->handler	= new QueueCommandExecutor( $this->queueConnection, $this->nr );
	}
}
