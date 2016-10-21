<?php

namespace HelePartnerSyncApi\Response;

use Exception;
use Throwable;

class ResponseFactory
{

	/**
	 * @var string
	 */
	private $secret;

	/**
	 * @param string $secret
	 */
	public function __construct($secret)
	{
		$this->secret = $secret;
	}

	/**
	 * @param array $data
	 * @return Response
	 */
	public function createSuccessResponse($data)
	{
		return new SuccessResponse($this->secret, $data);
	}

	/**
	 * @param Exception|Throwable $exception
	 * @return Response
	 */
	public function createErrorResponse($exception)
	{
		return new ErrorResponse($this->secret, $exception);
	}

}
