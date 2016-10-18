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
	 * @var string
	 */
	private $expectedVersion;

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
			throw new RequestException('Invalid JSON in request.');
		}

		if (!in_array($signatureAlgorithm, hash_algos(), true)) {
			throw new RequestException(sprintf('Unknown signature algorithm (%s) in request.', $signatureAlgorithm));
		}

		if (hash_hmac($signatureAlgorithm, $jsonData, $secret) !== $signature) {
			throw new RequestException('Signature in request is invalid.');
		}

		try {
			Validator::checkStructure($data, array(
				'expectedVersion' => Validator::TYPE_STRING,
				'method' => Validator::TYPE_STRING,
				'data' => Validator::TYPE_ARRAY,
			));

		} catch (ValidatorException $e) {
			throw new RequestException('Invalid request: ' . $e->getMessage(), $e);
		}

		$this->data = $data['data'];
		$this->method = $data['method'];
		$this->expectedVersion = $data['expectedVersion'];
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
	public function getExpectedVersion()
	{
		return $this->expectedVersion;
	}

}
