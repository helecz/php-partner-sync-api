<?php

namespace HelePartnerSyncApi;

use Exception;
use HelePartnerSyncApi\Methods\Method;
use HelePartnerSyncApi\Responses\ErrorResponse;
use HelePartnerSyncApi\Responses\SuccessResponse;
use LogicException;
use Throwable;

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
	 * @return SuccessResponse|ErrorResponse
	 */
	public function run(Request $request)
	{
		try {
			$this->validateRequest($request);

			$method = $this->getMethod($request->getMethod());

			$responseData = call_user_func_array(
				$method->getCallback(),
				$method->parseRequestData($request->getData())
			);

		} catch (Exception $e) {
			return new ErrorResponse($e->getMessage());

		} catch (Throwable $e) {
			return new ErrorResponse($e->getMessage());
		}

		return new SuccessResponse(
			$method->parseResponseData($responseData)
		);
	}

	private function validateRequest(Request $request)
	{
		if ($request->getExpectedVersion() !== self::VERSION) {
			throw new LogicException(sprintf('Request expected version %s, but client is %s', $request->getExpectedVersion(), self::VERSION));
		}

		if ($request->getPartnerId() !== $this->partnerId) {
			throw new LogicException(sprintf('Request was identified by ID %s, but client is %s', $request->getPartnerId(), $this->partnerId));
		}
	}

	/**
	 * @param string $method
	 * @return Method
	 */
	private function getMethod($method)
	{
		if (!isset($this->methods[$method])) {
			throw new LogicException(sprintf('Method %s was not registered!', $method));
		}

		return $this->methods[$method];
	}

}
