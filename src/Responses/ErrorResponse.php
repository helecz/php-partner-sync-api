<?php

namespace HelePartnerSyncApi\Responses;

use Exception;
use HelePartnerSyncApi\Exception as HeleException;
use Throwable;

class ErrorResponse extends Response
{

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
		if ($this->exception instanceof HeleException) {
			return $this->exception->getMessage();
		}

		return 'Internal server error';
	}

	/**
	 * @return Exception|Throwable
	 */
	public function getException()
	{
		return $this->exception;
	}

}
