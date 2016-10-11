<?php

namespace HelePartnerSyncApi;

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
		return array(
			'error' => $this->message
		);
	}

	/**
	 * @return bool
	 */
	public function isSuccessful()
	{
		return false;
	}

}