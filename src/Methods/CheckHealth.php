<?php

namespace HelePartnerSyncApi\Methods;

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
	 * @param mixed $data
	 * @return mixed
	 */
	protected function parseResponseData($data)
	{
		return $data;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	protected function parseRequestData($data)
	{
		return array($data);
	}

}
