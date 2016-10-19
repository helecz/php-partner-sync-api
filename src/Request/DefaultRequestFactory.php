<?php

namespace HelePartnerSyncApi\Request;

use HelePartnerSyncApi\Client;

class DefaultRequestFactory implements RequestFactory
{

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

	/**
	 * @param string $body
	 * @param string[] $headers
	 * @return Request
	 */
	public function createRequest($body, array $headers)
	{
		if (!isset($headers[Client::HEADER_SIGNATURE])) {
			throw new RequestException(sprintf('Missing %s header in HTTP request', Client::HEADER_SIGNATURE));
		}

		if (!isset($headers[Client::HEADER_SIGNATURE_ALGORITHM])) {
			throw new RequestException(sprintf('Missing %s header in HTTP request', Client::HEADER_SIGNATURE_ALGORITHM));
		}

		return new Request($body, $this->secret, $headers[Client::HEADER_SIGNATURE], $headers[Client::HEADER_SIGNATURE_ALGORITHM]);
	}

}
