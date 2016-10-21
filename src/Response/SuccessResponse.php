<?php

namespace HelePartnerSyncApi\Response;

use HelePartnerSyncApi\Validator;

class SuccessResponse extends Response
{

	const OK_MESSAGE = 'ok';

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @param string $secret
	 * @param array $data
	 */
	public function __construct($secret, $data)
	{
		Validator::checkArray($data);

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
	 * @return int
	 */
	public function getHttpCode()
	{
		return 200;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return self::OK_MESSAGE;
	}

}
