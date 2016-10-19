<?php

namespace HelePartnerSyncApi;

use HelePartnerSyncApi\Method\Method;
use HelePartnerSyncApi\Request\Request;
use HelePartnerSyncApi\Request\RequestFactory;
use HelePartnerSyncApi\Response\SuccessResponse;
use PHPUnit_Framework_TestCase;

class ClientTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var string
	 */
	private $secret = 'secret';

	/**
	 * @var string
	 */
	private $method = 'foo';

	public function testSuccess()
	{
		$data = array($this->method => 'bar');
		$method = $this->getMethodMock($data);
		$requestFactory = $this->getRequestFactoryMock();

		$responseFactory = $this->getMock('HelePartnerSyncApi\Response\ResponseFactory');
		$responseFactory->expects(self::once())
			->method('createSuccessResponse')
			->with($data)
			->willReturn(new SuccessResponse($this->secret, $data));

		$client = new Client($requestFactory, $responseFactory);
		$client->registerMethod($method);

		$response = $client->run();

		$this->assertTrue($response->isSuccessful());
		$this->assertSame($data, $response->getData());
	}

	/**
	 * @param array $dataToReturn
	 * @return Method
	 */
	private function getMethodMock(array $dataToReturn)
	{
		$method = $this->getMockBuilder('HelePartnerSyncApi\Method\Method')
			->disableOriginalConstructor()
			->getMock();

		$method->expects(self::once())
			->method('call')
			->willReturn($dataToReturn);

		$method->expects(self::once())
			->method('getName')
			->willReturn($this->method);

		return $method;
	}

	/**
	 * @return RequestFactory
	 */
	private function getRequestFactoryMock()
	{
		$factory = $this->getMockBuilder('HelePartnerSyncApi\Request\RequestFactory')
			->getMock();

		$factory->expects(self::once())
			->method('createRequest')
			->willReturn($this->getRequestMock());

		return $factory;
	}

	/**
	 * @return Request
	 */
	private function getRequestMock()
	{
		$request = $this->getMockBuilder('HelePartnerSyncApi\Request\Request')
			->disableOriginalConstructor()
			->getMock();

		$request->expects(self::atLeastOnce())
			->method('getMethod')
			->willReturn($this->method);

		$request->expects(self::atLeastOnce())
			->method('getExpectedVersion')
			->willReturn(Client::VERSION);

		return $request;
	}

}
