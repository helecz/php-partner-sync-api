<?php

namespace HelePartnerSyncApi\Methods;

class CheckHealth extends Method
{

	public function __construct()
	{
		parent::__construct(function () {
			return func_get_args();
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
	public function parseResponseData($data)
	{
		return $data;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public function parseRequestData(array $data)
	{
		return $data;
	}

}
