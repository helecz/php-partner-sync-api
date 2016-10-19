<?php

namespace HelePartnerSyncApi;

use Closure;
use HelePartnerSyncApi\Method\CancelReservation;
use HelePartnerSyncApi\Method\CheckHealth;
use HelePartnerSyncApi\Method\CreateReservation;
use HelePartnerSyncApi\Method\GetSlots;
use HelePartnerSyncApi\Request\DefaultRequestFactory;
use HelePartnerSyncApi\Response\DefaultResponseFactory;

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
		$this->client = new Client(
			new DefaultRequestFactory($secret),
			new DefaultResponseFactory($secret)
		);
		$this->client->registerMethod(new CheckHealth());
	}

	/**
	 * @param Closure $callback function (DateTime $date, array $parameters)
	 */
	public function onGetSlots(Closure $callback)
	{
		$this->client->registerMethod(new GetSlots($callback));
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
