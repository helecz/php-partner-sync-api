<?php

namespace HelePartnerSyncApi;

use Exception;

class AbortException extends Exception
{

	/**
	 * @var Response
	 */
	private $response;

	/**
	 * @param Response $response
	 */
	public function __construct(Response $response)
	{
		$this->response = $response;
	}

	/**
	 * @return Response
	 */
	public function getResponse()
	{
		return $this->response;
	}

}