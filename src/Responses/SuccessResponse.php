<?php

namespace HelePartnerSyncApi\Responses;

class SuccessResponse extends Response
{

	/**
	 * @var mixed[]
	 */
	private $data;

	/**
	 * @param string $secret
	 * @param array $data
	 */
	public function __construct($secret, array $data)
	{
		parent::__construct($secret);
		$this->data = $data;
	}

	/**
	 * @return array
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
