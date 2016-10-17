<?php

namespace HelePartnerSyncApi;

use Closure;
use HelePartnerSyncApi\Methods\CheckHealth;
use HelePartnerSyncApi\Methods\CheckSlots;
use HelePartnerSyncApi\Methods\CreateReservation;

class Application
{

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var RequestFactory
	 */
	private $requestFactory;

	/**
	 * @param string $secret
	 */
	public function __construct($secret)
	{
		$this->client = new Client($secret);
		$this->client->registerMethod(new CheckHealth());

		$this->requestFactory = new BaseRequestFactory();
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

	public function run()
	{
		$this->client->run($this->requestFactory)
			->render();
	}

}
