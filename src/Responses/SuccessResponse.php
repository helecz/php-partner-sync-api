<?php

namespace HelePartnerSyncApi\Responses;

class SuccessResponse extends Response
{

	/**
	 * @var mixed
	 */
	private $data;

	/**
	 * @param string $secret
	 * @param mixed $data
	 */
	public function __construct($secret, $data)
	{
		parent::__construct($secret);
		$this->data = $data;
	}

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return bool
	 */
	public function isSuccessful()
	{
		return true;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return 'ok';
	}

}
