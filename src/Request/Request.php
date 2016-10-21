<?php

namespace HelePartnerSyncApi\Request;

use HelePartnerSyncApi\ValidationException;
use HelePartnerSyncApi\Validator;

class Request
{

	const KEY_EXPECTED_VERSION = 'expectedVersion';
	const KEY_METHOD = 'method';
	const KEY_DATA = 'data';

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
				self::KEY_EXPECTED_VERSION => Validator::TYPE_STRING,
				self::KEY_METHOD => Validator::TYPE_STRING,
				self::KEY_DATA => Validator::TYPE_ARRAY,
			));

		} catch (ValidationException $e) {
			throw new RequestException('Invalid request: ' . $e->getMessage(), $e);
		}

		$this->data = $data[self::KEY_DATA];
		$this->method = $data[self::KEY_METHOD];
		$this->expectedVersion = $data[self::KEY_EXPECTED_VERSION];
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
