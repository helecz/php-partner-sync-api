<?php

namespace HelePartnerSyncApi\Method;

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

		$this->assertNull($response);
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

	public function testExceptions()
	{
		$startDateTime = new DateTime('+1 hour');
		$endDateTime = new DateTime('+2 hours');
		$this->checkException(
			null,
			null,
			'Array expected, NULL given.'
		);
		$this->checkException(
			array(),
			null,
			'Missing keys (startDateTime, endDateTime, quantity, parameters)'
		);
		$this->checkException(
			array(
				'startDateTime' => $startDateTime->format(DateTime::W3C),
				'endDateTime' => $endDateTime->format(DateTime::W3C),
				'quantity' => 1,
				'parameters' => array(),
			),
			'response-data',
			'Null expected, string (response-data) given.'
		);
		$this->checkException(
			array(
				'startDateTime' => 1,
				'endDateTime' => $endDateTime->format(DateTime::W3C),
				'quantity' => 1,
				'parameters' => array(),
			),
			null,
			'DateTime expected, integer (1) given.'
		);
		$this->checkException(
			array(
				'startDateTime' => $startDateTime->format(DateTime::W3C),
				'endDateTime' => $endDateTime->format(DateTime::W3C),
				'quantity' => 'quantity',
				'parameters' => array(),
			),
			null,
			'Int expected, string (quantity) given.'
		);
		$this->checkException(
			array(
				'startDateTime' => $startDateTime->format(DateTime::W3C),
				'endDateTime' => $endDateTime->format(DateTime::W3C),
				'quantity' => 1,
				'parameters' => 'param',
			),
			null,
			'Array expected, string (param) given.'
		);
	}

	/**
	 * @param mixed $requestData
	 * @param mixed $responseData
	 * @param string $error
	 */
	private function checkException($requestData, $responseData, $error)
	{
		try {
			$request = $this->getRequestMock($requestData);
			$method = new CreateReservation(function () use ($responseData) {
				return $responseData;
			});
			$method->call($request);
			$this->fail('Expected exception to be thrown');

		} catch (MethodException $e) {
			$this->assertContains($error, $e->getMessage());
		}
	}

}
