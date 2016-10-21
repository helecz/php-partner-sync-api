<?php

namespace HelePartnerSyncApi\Response;

use HelePartnerSyncApi\Client;
use PHPUnit_Framework_TestCase;

// @codingStandardsIgnoreStart
function header($header)
{
	ResponseTest::$headers[$header] = true;
}
// @codingStandardsIgnoreEnd

class ResponseTest extends PHPUnit_Framework_TestCase
{

	public static $headers = array();

	public function test()
	{
		$secret = 'secret';
		$data = array(
			'key' => 'value',
		);
		$jsonData = json_encode(array(
			Response::KEY_SUCCESS => null,
			Response::KEY_MESSAGE => null,
			Response::KEY_DATA => $data,
		), defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0);

		$response = $this->createResponseMock($secret, $data);

		ob_start();
		$response->render();
		$responseJson = ob_get_clean();

		$this->assertSame($jsonData, $responseJson);

		$this->assertArrayHasKey('HTTP/1.1 200', self::$headers);
		$this->assertArrayHasKey('Content-Type: application/json', self::$headers);
		$this->assertArrayHasKey(Client::HEADER_SIGNATURE . ': ' . hash_hmac(Response::SIGNATURE_ALGORITHM, $jsonData, $secret), self::$headers);
		$this->assertArrayHasKey(Client::HEADER_SIGNATURE_ALGORITHM . ': ' . Response::SIGNATURE_ALGORITHM, self::$headers);
	}

	/**
	 * @param string $secret
	 * @param array $returnData
	 * @return Response
	 */
	private function createResponseMock($secret, $returnData)
	{
		$response = $this->getMockBuilder('HelePartnerSyncApi\Response\Response')
			->setConstructorArgs(array($secret))
			->getMockForAbstractClass();
		$response->expects(self::once())
			->method('getHttpCode')
			->willReturn(200);
		$response->expects(self::once())
			->method('getData')
			->willReturn($returnData);

		return $response;
	}

}
