<?php

namespace HelePartnerSyncApi;

use LogicException;
use PHPUnit_Framework_TestCase;

class RequestTest extends PHPUnit_Framework_TestCase
{

	public function testSuccess()
	{
		$headers = array(
			Client::HEADER_SIGNATURE => 'moo',
		);
		$body = json_encode(array(
			'data' => 'foo',
			'method' => 'bar',
			'expectedVersion' => 'boo',
		));
		$request = new Request($headers, $body);
		$this->assertSame($body, $request->getRawBody());
		$this->assertSame('foo', $request->getData());
		$this->assertSame('bar', $request->getMethod());
		$this->assertSame('boo', $request->getExpectedVersion());
		$this->assertSame('moo', $request->getSignature());
	}

	public function testFailures()
	{
		$this->checkException(array(), '', 'Invalid JSON');
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
			new Request($headers, $body);
			$this->fail('Exception was expected!');

		} catch (LogicException $e) {
			$this->assertContains($message, $e->getMessage());
		}
	}

}
