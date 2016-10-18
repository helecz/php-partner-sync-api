<?php

namespace HelePartnerSyncApi\Response;

use Exception;
use HelePartnerSyncApi\Request\RequestException;
use PHPUnit_Framework_TestCase;

class ErrorResponseTest extends PHPUnit_Framework_TestCase
{

	public function test()
	{
		$message = 'foo message';
		$exception = new RequestException($message);
		$response = new ErrorResponse('secret', $exception);

		$this->assertSame($exception, $response->getException());
		$this->assertSame($message, $response->getMessage());
		$this->assertNull($response->getData());
		$this->assertFalse($response->isSuccessful());
	}

	public function testInternalError()
	{
		$exception = new Exception(uniqid());
		$response = new ErrorResponse('secret', $exception);

		$this->assertSame($exception, $response->getException());
		$this->assertSame('Internal server error', $response->getMessage());
		$this->assertNull($response->getData());
		$this->assertFalse($response->isSuccessful());
	}

}
