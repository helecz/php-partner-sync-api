<?php

namespace HelePartnerSyncApi;

use Closure;
use HelePartnerSyncApi\Methods\CheckSlots;
use HelePartnerSyncApi\Methods\CreateReservation;
use HelePartnerSyncApi\Methods\Method;
use LogicException;

class Client
{

	const VERSION = '1.0.0';

	/**
	 * @var string
	 */
	private $partnerId;

	/**
	 * @var Method[]
	 */
	private $methods;

	public function __construct($partnerId)
	{
		$this->partnerId = $partnerId;
	}

	public function registerMethod(Method $method)
	{
		$this->methods[$method->getName()] = $method;
	}

	/**
	 * @param Request $request
	 * @throws AbortException
	 */
	public function run(Request $request)
	{
		$this->validateRequest($request);

		$method = $this->getMethod($request->getMethod());

		$responseData = call_user_func_array(
			$method->getCallback(),
			$method->parseRequestData($request->getData())
		);

		$method->validateResponseData($responseData);

		throw new AbortException(new SuccessResponse($responseData));
	}

	private function validateRequest(Request $request)
	{
		if ($request->getExpectedVersion() !== self::VERSION) {
			throw new LogicException(sprintf('Request expected version %s, but client is %s', $request->getExpectedVersion(), self::VERSION));
		}
	}

	/**
	 * @param string $method
	 * @return Method
	 */
	private function getMethod($method)
	{
		if (!isset($this->methods[$method])) {
			throw new LogicException("Unknown method $method!");
		}

		return $this->methods[$method];
	}
}