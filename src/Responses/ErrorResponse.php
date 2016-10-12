<?php

namespace HelePartnerSyncApi\Responses;

class ErrorResponse extends Response
{

	/**
	 * @var string
	 */
	private $message;

	/**
	 * @param string $secret
	 * @param string $message
	 */
	public function __construct($secret, $message)
	{
		parent::__construct($secret);
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
