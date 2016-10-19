<?php

namespace HelePartnerSyncApi\Method;

class CheckHealth extends Method
{

	public function __construct()
	{
		parent::__construct(function () {
			// no action
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
	protected function constructRequestData($data)
	{
		return array();
	}

	/**
	 * @param mixed $data
	 * @return array
	 */
	protected function constructResponseData($data)
	{
		return array();
	}

}
