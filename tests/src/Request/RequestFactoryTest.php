<?php

namespace HelePartnerSyncApi\Request;

use HelePartnerSyncApi\Client;
use PHPUnit_Framework_TestCase;

class RequestFactoryTest extends PHPUnit_Framework_TestCase
{

	public function testCreateRequest()
	{
		$algo = 'sha1';
		$secret = 'secret';
		$arguments = array('value');
		$method = 'fooMethod';
		$data = array(
			Request::KEY_DATA => $arguments,
			Request::KEY_EXPECTED_VERSION => Client::VERSION,
			Request::KEY_METHOD => $method,
		);

		$requestData = json_encode($data);
		$requestFactory = new RequestFactory($secret);
		$request = $requestFactory->createRequest($requestData, array(
			Client::HEADER_SIGNATURE => hash_hmac($algo, $requestData, $secret),
			Client::HEADER_SIGNATURE_ALGORITHM => 'sha1',
		));

		$this->assertInstanceOf('HelePartnerSyncApi\Request\Request', $request);
		$this->assertSame($arguments, $request->getData());
		$this->assertSame(Client::VERSION, $request->getExpectedVersion());
		$this->assertSame($method, $request->getMethod());
	}

	/**
	 * @expectedException \HelePartnerSyncApi\Request\RequestException
	 * @expectedExceptionMessage Missing X-Hele-Signature header in HTTP request
	 */
	public function testMissingSignatureHeader()
	{
		$secret = 'secret';
		$requestFactory = new RequestFactory($secret);
		$requestFactory->createRequest(json_encode(array()), array());
	}

	/**
	 * @expectedException \HelePartnerSyncApi\Request\RequestException
	 * @expectedExceptionMessage Missing X-Hele-Signature-Algorithm header in HTTP request
	 */
	public function testMissingSignatureAlgorithmHeader()
	{
		$secret = 'secret';
		$requestFactory = new RequestFactory($secret);
		$requestFactory->createRequest(json_encode(array()), array(
			Client::HEADER_SIGNATURE => 'fooSignature',
		));
	}

}
