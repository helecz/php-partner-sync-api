<?php

namespace HelePartnerSyncApi;

use Exception;
use HelePartnerSyncApi\Methods\Method;
use HelePartnerSyncApi\Request\RequestFactory;
use HelePartnerSyncApi\Responses\ErrorResponse;
use HelePartnerSyncApi\Responses\SuccessResponse;
use Throwable;

class Client
{

	const VERSION = '1.0.0';

	const HEADER_SIGNATURE = 'X-Hele-Signature';
	const HEADER_SIGNATURE_ALGORITHM = 'X-Hele-Signature-Algorithm';

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
	 * @var RequestFactory
	 */
	private $requestFactory;

	/**
	 * @param string $secret
	 * @param RequestFactory $requestFactory
	 */
	public function __construct($secret, RequestFactory $requestFactory)
	{
		Validator::checkString($secret);

		$this->secret = $secret;
		$this->requestFactory = $requestFactory;
	}

	public function registerMethod(Method $method)
	{
		$this->methods[$method->getName()] = $method;
	}

	/**
	 * @return SuccessResponse|ErrorResponse
	 */
	public function run()
	{
		try {
			$request = $this->requestFactory->createRequest();

			$method = $this->getMethod($request->getMethod());

			$responseData = $method->call($request);

		} catch (Exception $e) {
			return new ErrorResponse($this->secret, $e);

		} catch (Throwable $e) {
			return new ErrorResponse($this->secret, $e);
		}

		return new SuccessResponse(
			$this->secret,
			$responseData
		);
	}

	/**
	 * @param string $method
	 * @return Method
	 * @throws ClientException
	 */
	private function getMethod($method)
	{
		if (!isset($this->methods[$method])) {
			throw new ClientException(sprintf('Method %s was not registered!', $method));
		}

		return $this->methods[$method];
	}

}
