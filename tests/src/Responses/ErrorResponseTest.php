<?php

namespace HelePartnerSyncApi\Responses;

use Exception;
use PHPUnit_Framework_TestCase;

class ErrorResponseTest extends PHPUnit_Framework_TestCase
{

	public function test()
	{
		$exception = new Exception(uniqid());
		$response = new ErrorResponse('secret', $exception);

		$this->assertSame($exception->getMessage(), $response->getMessage());
		$this->assertSame(array(), $response->getData());
		$this->assertFalse($response->isSuccessful());
	}

}
