<?php

namespace HelePartnerSyncApi\Request;

use HelePartnerSyncApi\Client;

class Request
{

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var string
	 */
	private $method;

	/**
	 * @param string $jsonData
	 * @param string $secret
	 * @param string $signature
	 * @param string $signatureAlgorithm
	 */
	public function __construct($jsonData, $secret, $signature, $signatureAlgorithm)
	{
		$data = json_decode($jsonData, true);

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

		if (!in_array($signatureAlgorithm, hash_algos(), true)) {
			throw new RequestException(sprintf(
				'Unknown signature algorithm `%s` in HTTP Request. Supported algorithms: %s',
				$signatureAlgorithm,
				implode(', ', hash_algos())
			));
		}

		if (hash_hmac($signatureAlgorithm, $jsonData, $secret) !== $signature) {
			throw new RequestException('Signature in HTTP Request is invalid!');
		}

		$this->data = $data['data'];
		$this->method = $data['method'];
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

}
