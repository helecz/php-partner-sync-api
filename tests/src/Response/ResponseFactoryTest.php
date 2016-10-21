<?php

namespace HelePartnerSyncApi\Response;

use Exception;
use PHPUnit_Framework_TestCase;

class ResponseFactoryTest extends PHPUnit_Framework_TestCase
{

	public function testCreateSuccessResponse()
	{
		$data = array(
			'key' => 'value',
		);

		$responseFactory = new ResponseFactory('secret');
		$response = $responseFactory->createSuccessResponse($data);

		$this->assertInstanceOf('HelePartnerSyncApi\Response\SuccessResponse', $response);
		$this->assertSame($data, $response->getData());
	}

	public function testCreateErrorResponse()
	{
		$exception = new Exception('Foo message');

		$responseFactory = new ResponseFactory('secret');
		$response = $responseFactory->createErrorResponse($exception);

		$this->assertInstanceOf('HelePartnerSyncApi\Response\ErrorResponse', $response);
		$this->assertSame(ErrorResponse::MESSAGE_INTERNAL_SERVER_ERROR, $response->getMessage());
	}

}
