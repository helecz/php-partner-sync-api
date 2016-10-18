<?php

namespace HelePartnerSyncApi\Method;

use DateTime;

class CancelReservationTest extends MethodTestCase
{

	public function testSuccess()
	{
		$startDateTime = new DateTime('+1 hour');
		$endDateTime = new DateTime('+2 hours');
		$request = $this->getRequestMock(array(
			'startDateTime' => $startDateTime->format(DateTime::W3C),
			'endDateTime' => $endDateTime->format(DateTime::W3C),
			'quantity' => 1,
			'parameters' => array(),
		));

		$method = new CancelReservation(function () {
			// no action
		});
		$response = $method->call($request);

		$this->assertSame(array(), $response);
	}

}
