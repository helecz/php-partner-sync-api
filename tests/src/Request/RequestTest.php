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

		$request = new Request($body, $secret, $signature, Client::SIGNATURE_ALGORITHM);
		$this->assertSame('foo', $request->getData());
		$this->assertSame('bar', $request->getMethod());
	}

	public function getTestExceptionData()
	{
		return array(
			array(
				'',
				'',
				'',
				'Invalid JSON in HTTP request body',
			),
			array(
				'{}',
				'',
				'',
				'Missing data field in HTTP request body',
			),
			array(
				'{"data":1}',
				'',
				'',
				'Missing method field in HTTP request body',
			),
			array(
				'{"data":1, "method":1}',
				'',
				'',
				'Missing expectedVersion field in HTTP request body',
			),
			array(
				'{"data":1, "method":1, "expectedVersion": "0.0.1"}',
				'',
				'',
				'Request expected version 0.0.1, but client is 1.0.0',
			),
			array(
				'{"data":1, "method":1, "expectedVersion": "1.0.0"}',
				'abc',
				'fooAlgo',
				'Unknown signature algorithm `fooAlgo` in HTTP Request',
			),
			array(
				'{"data":1, "method":1, "expectedVersion": "1.0.0"}',
				'abc',
				'sha1',
				'Signature in HTTP Request is invalid!',
			),
		);
	}

	/**
	 * @param string $body
	 * @param string $signature
	 * @param string $signatureAlgorithm
	 * @param string $message
	 *
	 * @dataProvider getTestExceptionData
	 */
	public function testException($body, $signature, $signatureAlgorithm, $message)
	{
		try {
			new Request($body, '', $signature, $signatureAlgorithm);
			$this->fail('Exception was expected!');

		} catch (RequestException $e) {
			$this->assertContains($message, $e->getMessage());
		}
	}

}
