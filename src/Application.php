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

	public function run()
	{
		$request = $this->getRequest();
		if (!$this->isHeleRequest($request)) {
			return;
		}

		$this->client->run($request)
			->render();

		exit;
	}

	/**
	 * @param Request $request
	 * @return bool
	 */
	private function isHeleRequest(Request $request)
	{
		return $request->hasHeader(Client::HEADER_SIGNATURE)
			&& $request->hasHeader(Client::HEADER_SIGNATURE_ALGORITHM);
	}

	/**
	 * @return Request
	 */
	private function getRequest()
	{
		if ($this->request === null) {
			$this->request = new Request($this->getHeaders(), file_get_contents('php://input'));
		}
		return $this->request;
	}

	/**
	 * @return string[]
	 */
	private function getHeaders()
	{
		if (function_exists('apache_request_headers')) {
			return apache_request_headers();
		}

		$headers = array();
		foreach ($_SERVER as $k => $v) {
			if (strncmp($k, 'HTTP_', 5) == 0) {
				$k = substr($k, 5);
			} elseif (strncmp($k, 'CONTENT_', 8)) {
				continue;
			}
			$headers[strtr($k, '_', '-')] = $v;
		}

		return $headers;
	}

}
