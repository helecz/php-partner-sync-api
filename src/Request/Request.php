<?php

namespace HelePartnerSyncApi\Request;

use HelePartnerSyncApi\Validator;
use HelePartnerSyncApi\ValidatorException;

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

		try {
			Validator::checkStructure($data, array(
				'expectedVersion' => Validator::TYPE_STRING,
				'method' => Validator::TYPE_STRING,
				'data' => Validator::TYPE_ARRAY,
			));

		} catch (ValidatorException $e) {
			throw new RequestException('Invalid Http request: ' . $e->getMessage(), $e);
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
