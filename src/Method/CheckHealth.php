<?php

namespace HelePartnerSyncApi\Method;

class CheckHealth extends Method
{

	public function __construct()
	{
		parent::__construct(function ($data) {
			return $data;
		});
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'checkHealth';
	}

	/**
	 * @param array $data
	 * @return array
	 */
	protected function parseRequestData($data)
	{
		return array($data);
	}

	/**
	 * @param mixed $data
	 * @return array
	 */
	protected function parseResponseData($data)
	{
		return array(
			'requestData' => $data,
		);
	}

}
