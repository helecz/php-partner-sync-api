<?php

namespace HelePartnerSyncApi\Methods;

use PHPUnit_Framework_TestCase;

abstract class MethodTestCase extends PHPUnit_Framework_TestCase
{

	/**
	 * @param mixed $dataToReturn
	 * @return \HelePartnerSyncApi\Request
	 */
	protected function getRequestMock($dataToReturn)
	{
		$request = $this->getMockBuilder('HelePartnerSyncApi\Request\Request')
			->disableOriginalConstructor()
			->getMock();

		$request->expects(self::once())
			->method('getData')
			->willReturn($dataToReturn);

		return $request;
	}

}
