<?php

namespace HelePartnerSyncApi\Response;

use Exception;
use Throwable;

interface ResponseFactory
{

	/**
	 * @param array $data
	 * @return Response
	 */
	public function createSuccessResponse($data);

	/**
	 * @param Exception|Throwable $exception
	 * @return Response
	 */
	public function createErrorResponse($exception);

}
