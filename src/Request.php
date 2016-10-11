<?php

namespace HelePartnerSyncApi;

use LogicException;

class Request
{

	/**
	 * @var mixed[]
	 */
	private $data;

	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var string
	 */
	private $partnerId;

	/**
	 * @var string
	 */
	private $expectedVersion;

	/**
	 * @param string $httpBody
	 */
	public function __construct($httpBody)
	{
		$this->parseBody(json_decode($httpBody));
	}

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * @return mixed[]
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function getPartnerId()
	{
		return $this->partnerId;
	}

	/**
	 * @return string
	 */
	public function getExpectedVersion()
	{
		return $this->expectedVersion;
	}

	private function parseBody($data)
	{
		if (!is_array($data)) {
			throw new LogicException('Invalid JSON in HTTP request body');
		}

		if (!isset($data['data'])) {
			throw new LogicException('Missing data field in HTTP request body');
		}

		if (!isset($data['method'])) {
			throw new LogicException('Missing method field in HTTP request body');
		}

		if (!isset($data['partnerId'])) {
			throw new LogicException('Missing partnerId field in HTTP request body');
		}

		if (!isset($data['expectedVersion'])) {
			throw new LogicException('Missing expectedVersion field in HTTP request body');
		}

		$this->data = $data['data'];
		$this->method = $data['method'];
		$this->partnerId = $data['partnerId'];
		$this->expectedVersion = $data['expectedVersion'];
	}


}