<?php

namespace HelePartnerSyncApi\Request;

use HelePartnerSyncApi\Client;

class Request
{

	/**
	 * @var string
	 */
	private $rawBody;

	/**
	 * @var string
	 */
	private $secret;

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
	 * @param string $secret
	 */
	public function __construct(array $headers, $httpBody, $secret)
	{
		$this->signature = isset($headers[Client::HEADER_SIGNATURE]) ? $headers[Client::HEADER_SIGNATURE] : null;
		$this->rawBody = $httpBody;
		$this->headers = $headers;
		$this->secret = $secret;
		$this->parseBody(json_decode($httpBody, true));
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
			throw new RequestException('Invalid JSON in HTTP request body');
		}

		if (!isset($data['data'])) {
			throw new RequestException('Missing data field in HTTP request body');
		}

		if (!isset($data['method'])) {
			throw new RequestException('Missing method field in HTTP request body');
		}

		if (!isset($data['expectedVersion'])) {
			throw new RequestException('Missing expectedVersion field in HTTP request body');
		}

		if ($data['expectedVersion'] !== Client::VERSION) {
			throw new RequestException(sprintf('Request expected version %s, but client is %s', $data['expectedVersion'], Client::VERSION));
		}

		if (hash_hmac(Client::SIGNATURE_ALGORITHM, $this->rawBody, $this->secret) !== $this->signature) {
			throw new RequestException('Signature in HTTP Request is invalid!');
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
