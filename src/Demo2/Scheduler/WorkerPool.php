<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace cwc24\mqtt\Demo2\Scheduler;

use cwc24\mqtt\Demo2\Worker\Daemon as Worker;

class WorkerPool
{
	private int $nrWorkers;

	private int $lastWorkerNr		= 0;

	private array $workers			= [];

	/**
	 *	@param		int			$nrWorkers
	 */
	public function __construct( int $nrWorkers )
	{
		$this->nrWorkers	= $nrWorkers;
		for( $i=1; $i<=$nrWorkers; $i++) {
			$this->workers[$i]	= 0;
		}
	}

	public function getLastWorkerNumber(): ?int
	{
		return $this->lastWorkerNr;
	}

	public function getNextFreeWorkerNumber(): ?int
	{
		for( $i=1; $i<=$this->nrWorkers; $i++) {
			$nextNr	= $this->lastWorkerNr + $i;
			if( $nextNr > $this->nrWorkers )
				$nextNr = 1;
			if( Worker::STATUS_FREE === $this->workers[$nextNr] ){
				$this->lastWorkerNr	= $nextNr;
				return $nextNr;
			}
		}

		return NULL;
	}

	public function index(): array
	{
		return $this->workers;
	}

	public function getNextWorker(): int
	{
		$nextNr	= $this->lastWorkerNr + 1;
		if( $nextNr > $this->nrWorkers )
			$nextNr = 1;
		$this->lastWorkerNr	= $nextNr;
		return $nextNr;
	}

	public function setWorkerStatus( int $nr, int $status ): self
	{
		$this->workers[$nr]	= $status;
		return $this;
	}
}