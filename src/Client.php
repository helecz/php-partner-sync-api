<?php

namespace HelePartnerSyncApi;

use Exception;
use HelePartnerSyncApi\Method\Method;
use HelePartnerSyncApi\Request\RequestFactory;
use HelePartnerSyncApi\Response\Response;
use HelePartnerSyncApi\Response\ResponseFactory;
use Throwable;

class Client
{

	const VERSION = '1.0';

	const HEADER_SIGNATURE = 'X-Hele-Signature';
	const HEADER_SIGNATURE_ALGORITHM = 'X-Hele-Signature-Algorithm';

	/**
	 * @var Method[]
	 */
	private $methods;

	/**
	 * @var RequestFactory
	 */
	private $requestFactory;

	/**
	 * @var ResponseFactory
	 */
	private $responseFactory;

	public function __construct(RequestFactory $requestFactory, ResponseFactory $responseFactory)
	{
		$this->requestFactory = $requestFactory;
		$this->responseFactory = $responseFactory;
	}

	public function registerMethod(Method $method)
	{
		$this->methods[$method->getName()] = $method;
	}

	/**
	 * @return Response
	 */
	public function run()
	{
		try {
			$request = $this->requestFactory->createRequest();

			if ($request->getExpectedVersion() !== self::VERSION) {
				throw new ClientException(sprintf('Server expected version %s, but current version is %s', $request->getExpectedVersion(), self::VERSION));
			}

			$method = $this->getMethod($request->getMethod());

			$responseData = $method->call($request);

		} catch (Exception $e) {
			return $this->responseFactory->createErrorResponse($e);

		} catch (Throwable $e) {
			return $this->responseFactory->createErrorResponse($e);
		}

		return $this->responseFactory->createSuccessResponse($responseData);
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
