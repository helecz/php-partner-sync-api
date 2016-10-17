<?php

namespace HelePartnerSyncApi\Responses;

use Exception;
use Throwable;

class ErrorResponse extends Response
{

	/**
	 * @var string
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
	 * @return null
	 */
	public function getData()
	{
		return null;
	}

	/**
	 * @return bool
	 */
	public function isSuccessful()
	{
		return false;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->exception->getMessage();
	}

	/**
	 * @return Exception|Throwable
	 */
	public function getException()
	{
		return $this->exception;
	}

}
