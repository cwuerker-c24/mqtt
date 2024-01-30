<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace cwc24\mqtt\Demo2\Worker;

use cwc24\mqtt\Demo2\Base\Connection;
use cwc24\mqtt\Demo2\Base\Sender;
use sskaje\mqtt\Message\PUBLISH as MqttPublishMessage;
use sskaje\mqtt\MessageHandler as MqttMessageHandler;
use sskaje\mqtt\MQTT as MqttClient;

class QueueCommandExecutor extends MqttMessageHandler
{
	private Sender $sender;

	private int $nr;

	/**
	 *	@param		Connection	$queueConnection
	 *	@param		int			$nr
	 */
	public function __construct( Connection $queueConnection, int $nr )
	{
		$this->sender		= new Sender( $queueConnection );
		$this->nr			= $nr;
	}

	public function publish( MqttClient $mqtt, MqttPublishMessage $publishObject ): void
	{
		$command	= $publishObject->getMessage();
		if( $command ){
			$response	= $this->dispatchCommand( $command );

//			custom response handling

			print $response.PHP_EOL;

		}

		$this->sender->publish( 'work._', $this->nr.' '.json_encode( ['status' => 0] ) );
	}

	//  --  PRIVATE  --  //

	private function dispatchCommand( string $command )
	{
//		... custom implementation ...

		return $command;

		return NULL;
	}
}