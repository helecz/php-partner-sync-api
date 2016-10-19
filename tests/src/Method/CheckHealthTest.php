<?php

namespace HelePartnerSyncApi\Method;

class CheckHealthTest extends MethodTestCase
{

	public function testSuccess()
	{
		$request = $this->getRequestMock(array());

		$method = new CheckHealth();
		$response = $method->call($request);

		$this->assertSame(array(), $response);
	}

}
