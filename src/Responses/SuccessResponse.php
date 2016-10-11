<?php

namespace HelePartnerSyncApi;

class SuccessResponse extends Response
{
	/**
	 * @var mixed[]
	 */
	private $data;

	public function __construct(array $data)
	{
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