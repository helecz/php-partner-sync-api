<?php

namespace HelePartnerSyncApi\Methods;

use DateTime;
use Exception;

class CreateReservationTest extends MethodTestCase
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

		$method = new CreateReservation(function () {
			// no action
		});
		$response = $method->call($request);

		$this->assertSame(array(), $response);
	}

	public function testFailure()
	{
		$startDateTime = new DateTime('+1 hour');
		$endDateTime = new DateTime('+2 hours');
		$request = $this->getRequestMock(array(
			'startDateTime' => $startDateTime->format(DateTime::W3C),
			'endDateTime' => $endDateTime->format(DateTime::W3C),
			'quantity' => 1,
			'parameters' => array(),
		));

		$exception = new Exception('Cannot create reservation');
		$method = new CreateReservation(function () use ($exception) {
			throw $exception;
		});
		try {
			$method->call($request);
			$this->fail('Exception expected');

		} catch (Exception $e) {
			$this->assertSame($exception, $e);
		}
	}

}
