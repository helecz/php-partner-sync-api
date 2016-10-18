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
			'data' => array('foo'),
			'method' => 'bar',
			'expectedVersion' => Client::VERSION,
		));

		$signature = hash_hmac(Client::SIGNATURE_ALGORITHM, $body, $secret);

		$request = new Request($body, $secret, $signature, Client::SIGNATURE_ALGORITHM);
		$this->assertSame(array('foo'), $request->getData());
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
				'Invalid Http request: Missing keys',
			),
			array(
				'{"data":[], "method": "foo", "expectedVersion": "1.0.0"}',
				'abc',
				'fooAlgo',
				'Unknown signature algorithm `fooAlgo` in HTTP Request',
			),
			array(
				'{"data":[], "method": "foo", "expectedVersion": "1.0.0"}',
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