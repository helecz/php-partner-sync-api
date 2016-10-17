<?php

namespace HelePartnerSyncApi;

use LogicException;

class Request
{

	/**
	 * @var string
	 */
	private $rawBody;

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var string[]
	 */
	private $headers;

	/**
	 * @var string|null
	 */
	private $signature;

	/**
	 * @var string
	 */
	private $expectedVersion;

	/**
	 * @param string[] $headers
	 * @param string $httpBody
	 */
	public function __construct(array $headers, $httpBody)
	{
		$this->signature = isset($headers[Client::HEADER_SIGNATURE]) ? $headers[Client::HEADER_SIGNATURE] : null;
		$this->rawBody = $httpBody;
		$this->headers = $headers;
		$this->parseBody(json_decode($httpBody, true));
	}

	/**
	 * @return string
	 */
	public function getRawBody()
	{
		return $this->rawBody;
	}

	/**
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function getSignature()
	{
		return $this->signature;
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

		if (!isset($data['expectedVersion'])) {
			throw new LogicException('Missing expectedVersion field in HTTP request body');
		}

		$this->data = $data['data'];
		$this->method = $data['method'];
		$this->expectedVersion = $data['expectedVersion'];
	}

	/**
	 * @param string $header
	 * @return bool
	 */
	public function hasHeader($header)
	{
		return array_key_exists($header, $this->headers);
	}

}
