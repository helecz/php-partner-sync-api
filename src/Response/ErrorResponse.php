<?php

namespace HelePartnerSyncApi\Response;

use Exception;
use HelePartnerSyncApi\HeleException;
use Throwable;

class ErrorResponse extends Response
{

	const INTERNAL_SERVER_ERROR_MESSAGE = 'Internal server error';

	/**
	 * @var Exception|Throwable
	 */
	private $exception;

	/**
	 * @param string $secret
	 * @param Exception|Throwable $exception
	 */
	public function __construct($secret, $exception)
	{
		parent::__construct($secret);
		$this->exception = $exception;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return array();
	}

	/**
	 * @return bool
	 */
	public function isSuccessful()
	{
		return false;
	}

	/**
	 * @return int
	 */
	public function getHttpCode()
	{
		return $this->exception instanceof HeleException ? 422 : 500;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		if ($this->exception instanceof HeleException) {
			return $this->exception->getMessage();
		}

		return self::INTERNAL_SERVER_ERROR_MESSAGE;
	}

}
