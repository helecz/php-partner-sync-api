<?php

namespace HelePartnerSyncApi;

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

		$client = new Client($this->secret, $requestFactory);
		$client->registerMethod($method);

		$response = $client->run();

		$this->assertTrue($response->isSuccessful());
		$this->assertSame($data, $response->getData());
	}

	/**
	 * @param array $dataToReturn
	 * @return \HelePartnerSyncApi\Methods\Method
	 */
	protected function getMethodMock(array $dataToReturn)
	{
		$method = $this->getMockBuilder('HelePartnerSyncApi\Methods\Method')
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
	 * @return \HelePartnerSyncApi\RequestFactory
	 */
	private function getRequestFactoryMock()
	{
		$factory = $this->getMockBuilder('HelePartnerSyncApi\RequestFactory')
			->getMock();

		$factory->expects(self::once())
			->method('createRequest')
			->willReturn($this->getRequestMock());

		return $factory;
	}

	/**
	 * @return \HelePartnerSyncApi\Request
	 */
	private function getRequestMock()
	{
		$rawBody = '{}';

		$request = $this->getMockBuilder('HelePartnerSyncApi\Request')
			->disableOriginalConstructor()
			->getMock();

		$request->expects(self::atLeastOnce())
			->method('getMethod')
			->willReturn($this->method);

		$request->expects(self::atLeastOnce())
			->method('getRawBody')
			->willReturn($rawBody);

		$request->expects(self::atLeastOnce())
			->method('getExpectedVersion')
			->willReturn(Client::VERSION);

		$request->expects(self::atLeastOnce())
			->method('getSignature')
			->willReturn(hash_hmac(Client::SIGNATURE_ALGORITHM, $rawBody, $this->secret));

		return $request;
	}

}
