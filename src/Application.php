<?php

namespace HelePartnerSyncApi;

use Closure;
use HelePartnerSyncApi\Methods\CheckSlots;
use HelePartnerSyncApi\Methods\CreateReservation;

class Application
{

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var Request|null
	 */
	private $request;

	/**
	 * @param string $secret
	 */
	public function __construct($secret)
	{
		$this->client = new Client($secret);
	}

	public function setRequest(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * @param Closure $callback function (DateTime $date)
	 */
	public function onCheckSlots(Closure $callback)
	{
		$this->client->registerMethod(new CheckSlots($callback));
	}

	/**
	 * @param Closure $callback function (DateTime $startDateTime, DateTime $endDateTime, int $quantity, array $parameters)
	 */
	public function onCreateReservation(Closure $callback)
	{
		$this->client->registerMethod(new CreateReservation($callback));
	}

	/**
	 * @throws AbortException
	 */
	public function run()
	{
		$request = $this->getRequest();

		if ($request->hasHeader(Client::HEADER_CALL)) {
			throw new AbortException($this->client->run($request));
		}
	}

	/**
	 * @return Request
	 */
	private function getRequest()
	{
		if ($this->request === null) {
			$this->request = new Request(getallheaders(), file_get_contents('php://input'));
		}
		return $this->request;
	}

}
