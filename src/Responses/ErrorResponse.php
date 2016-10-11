<?php

namespace HelePartnerSyncApi\Responses;

class ErrorResponse extends Response
{

	/**
	 * @var string
	 */
	private $message;

	public function __construct($message)
	{
		$this->message = $message;
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
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

}
