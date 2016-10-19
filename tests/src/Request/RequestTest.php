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

		$signature = hash_hmac('sha1', $body, $secret);

		$request = new Request($body, $secret, $signature, 'sha1');
		$this->assertSame(array('foo'), $request->getData());
		$this->assertSame('bar', $request->getMethod());
	}

	public function getTestExceptionData()
	{
		return array(
			array(
				'',
				'',
				'md5',
				'Invalid JSON in request',
			),
			array(
				'{}',
				'9b9585ab4f87eff122c8cd8e6fd94d358ed56f22',
				'sha1',
				'Invalid request: Missing keys',
			),
			array(
				'{"data":[], "method": "foo", "expectedVersion": "1.0.0"}',
				'abc',
				'fooAlgo',
				'Unknown signature algorithm (fooAlgo) in request',
			),
			array(
				'{"data":[], "method": "foo", "expectedVersion": "1.0.0"}',
				'abc',
				'sha1',
				'Signature in request is invalid',
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
