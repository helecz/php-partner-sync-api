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
	const SIGNATURE_HEADER = 'X-Hele-Signature';
	const SIGNATURE_ALGORITHM = 'sha1';

	/**
	 * @var string
	 */
	private $secret;

	/**
	 * @var Method[]
	 */
	private $methods;

	/**
	 * @param string $secret
	 */
	public function __construct($secret)
	{
		$this->secret = $secret;
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
			return new ErrorResponse($this->secret, $e->getMessage());

		} catch (Throwable $e) {
			return new ErrorResponse($this->secret, $e->getMessage());
		}

		return new SuccessResponse(
			$this->secret,
			$method->parseResponseData($responseData)
		);
	}

	private function validateRequest(Request $request)
	{
		if ($request->getExpectedVersion() !== self::VERSION) {
			throw new LogicException(sprintf('Request expected version %s, but client is %s', $request->getExpectedVersion(), self::VERSION));
		}

		if (hash_hmac(self::SIGNATURE_ALGORITHM, $request->getRawBody(), $this->secret) !== $request->getSignature()) {
			throw new LogicException('Signature in HTTP Request is invalid!');
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
