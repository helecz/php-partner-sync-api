<?php

namespace HelePartnerSyncApi\Method;

class CheckHealthTest extends MethodTestCase
{

	public function testSuccess()
	{
		$data = array(
			'foo' => 'fooValue',
			'bar' => 'barValue',
		);
		$request = $this->getRequestMock($data);

		$method = new CheckHealth();
		$response = $method->call($request);

		$this->assertSame(array(
			'requestData' => $data,
		), $response);
	}

}
