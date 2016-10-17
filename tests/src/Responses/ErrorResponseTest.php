<?php

namespace HelePartnerSyncApi\Responses;

use PHPUnit_Framework_TestCase;

class ErrorResponseTest extends PHPUnit_Framework_TestCase
{

	public function test()
	{
		$message = 'message';
		$response = new ErrorResponse('secret', $message);

		$this->assertSame($message, $response->getMessage());
		$this->assertSame(array(), $response->getData());
		$this->assertFalse($response->isSuccessful());
	}

}
