<?php

namespace HelePartnerSyncApi\Response;

use HelePartnerSyncApi\Client;

abstract class Response
{

	const SIGNATURE_ALGORITHM = 'sha1';

	const KEY_SUCCESS = 'success';
	const KEY_MESSAGE = 'message';
	const KEY_DATA = 'data';

	/**
	 * @var string
	 */
	private $secret;

	/**
	 * @param string $secret
	 */
	public function __construct($secret)
	{
		$this->secret = $secret;
	}

	public function render()
	{
		$response = json_encode(array(
			self::KEY_SUCCESS => $this->isSuccessful(),
			self::KEY_MESSAGE => $this->getMessage(),
			self::KEY_DATA => $this->getData(),
		), defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0);

		$signature = hash_hmac(self::SIGNATURE_ALGORITHM, $response, $this->secret);

		$this->send($signature, $response);
	}

	/**
	 * @return array
	 */
	abstract public function getData();

	/**
	 * @return bool
	 */
	abstract public function isSuccessful();

	/**
	 * @return int
	 */
	abstract public function getHttpCode();

	/**
	 * @return string
	 */
	abstract public function getMessage();

	/**
	 * @param string $signature
	 * @param string $response
	 */
	private function send($signature, $response)
	{
		header('HTTP/1.1 ' . $this->getHttpCode());
		header('Content-Type: application/json');
		header(Client::HEADER_SIGNATURE . ': ' . $signature);
		header(Client::HEADER_SIGNATURE_ALGORITHM . ': ' . self::SIGNATURE_ALGORITHM);

		echo $response;
	}

}
