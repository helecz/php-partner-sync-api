<?php

namespace HelePartnerSyncApi\Request;

use HelePartnerSyncApi\Client;
use PHPUnit_Framework_TestCase;

class RequestTest extends PHPUnit_Framework_TestCase
{

	public function testSuccess()
	{
		$secret = 'foo secret';

		$body = json_encode(array(
			'data' => 'foo',
			'method' => 'bar',
			'expectedVersion' => Client::VERSION,
		));

		$signature = hash_hmac(Client::SIGNATURE_ALGORITHM, $body, $secret);
		$headers = array(
			Client::HEADER_SIGNATURE => $signature,
		);

		$request = new Request($headers, $body, $secret);
		$this->assertSame('foo', $request->getData());
		$this->assertSame('bar', $request->getMethod());
		$this->assertSame(Client::VERSION, $request->getExpectedVersion());
		$this->assertSame($signature, $request->getSignature());
	}

	public function testFailures()
	{
		$this->checkException(array(), '', 'Invalid JSON in HTTP request body');
		$this->checkException(array(), '{}', 'Missing data field');
		$this->checkException(array(), '{"data":1}', 'Missing method field');
		$this->checkException(array(), '{"data":1, "method":1}', 'Missing expectedVersion field');
	}

	/**
	 * @param array $headers
	 * @param string $body
	 * @param string $message
	 */
	private function checkException(array $headers, $body, $message)
	{
		try {
			new Request($headers, $body, 'foo secret');
			$this->fail('Exception was expected!');

		} catch (RequestException $e) {
			$this->assertContains($message, $e->getMessage());
		}
	}

}
