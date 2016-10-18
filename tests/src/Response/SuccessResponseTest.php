<?php

namespace HelePartnerSyncApi\Response;

use PHPUnit_Framework_TestCase;

class SuccessResponseTest extends PHPUnit_Framework_TestCase
{

	public function test()
	{
		$data = array(
			'foo' => 'bar',
			'moo' => array(
				'baz',
			),
		);
		$response = new SuccessResponse('secret', $data);

		$this->assertSame('ok', $response->getMessage());
		$this->assertSame($data, $response->getData());
		$this->assertTrue($response->isSuccessful());
	}

}
