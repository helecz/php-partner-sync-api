<?php

namespace HelePartnerSyncApi;

use Closure;
use HelePartnerSyncApi\Method\CancelReservation;
use HelePartnerSyncApi\Method\CheckHealth;
use HelePartnerSyncApi\Method\CheckSlots;
use HelePartnerSyncApi\Method\CreateReservation;
use HelePartnerSyncApi\Request\DefaultRequestFactory;

class Application
{

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @param string $secret
	 */
	public function __construct($secret)
	{
		$this->client = new Client($secret, new DefaultRequestFactory($secret));
		$this->client->registerMethod(new CheckHealth());
	}

	/**
	 * @param Closure $callback function (DateTime $date, array $parameters)
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
	 * @param Closure $callback function (DateTime $startDateTime, DateTime $endDateTime, int $quantity, array $parameters)
	 */
	public function onCancelReservation(Closure $callback)
	{
		$this->client->registerMethod(new CancelReservation($callback));
	}

	public function run()
	{
		$this->client->run()->render();
	}

}
