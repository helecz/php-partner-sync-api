<?php

namespace HelePartnerSyncApi\Methods;

use DateTime;
use LogicException;

class CheckSlotsTest extends MethodTestCase
{

	public function testSuccess()
	{
		$today = new DateTime('today');
		$request = $this->getRequestMock(array(
			'date' => $today->format(DateTime::W3C),
			'parameters' => array(),
		));

		$startDateTime = new DateTime('+1 hour');
		$endDateTime = new DateTime('+2 hours');
		$closure = function () use ($startDateTime, $endDateTime) {
			return array(
				array(
					'startDateTime' => $startDateTime,
					'endDateTime' => $endDateTime,
					'capacity' => 1,
				),
			);
		};
		$method = new CheckSlots($closure);
		$response = $method->call($request);

		$this->assertSame(array(
			array(
				'startDateTime' => $startDateTime->format(DateTime::W3C),
				'endDateTime' => $endDateTime->format(DateTime::W3C),
				'capacity' => 1,
			),
		), $response);
	}

	public function testFailures()
	{
		$this->checkException(
			array(),
			array(),
			'Missing "date" field'
		);
		$this->checkException(
			array(
				'date' => 'now'
			),
			array(),
			'Missing "parameters" field'
		);
		$this->checkException(
			array(
				'date' => 'now',
				'parameters' => array(),
			),
			array(
				array(),
			),
			'Missing "startDateTime" field'
		);
		$this->checkException(
			array(
				'date' => 'now',
				'parameters' => array(),
			),
			array(
				array(
					'startDateTime' => new DateTime(),
				)
			),
			'Missing "endDateTime" field'
		);
		$this->checkException(
			array(
				'date' => 'now',
				'parameters' => array(),
			),
			array(
				array(
					'startDateTime' => new DateTime(),
					'endDateTime' => new DateTime(),
				)
			),
			'Missing "capacity" field'
		);
		$this->checkException(
			array(
				'date' => 'now',
				'parameters' => array(),
			),
			array(
				array(
					'startDateTime' => new DateTime(),
					'endDateTime' => new DateTime(),
					'capacity' => 'string',
				)
			),
			'expected to be integer, string given'
		);
		$this->checkException(
			array(
				'date' => 'now',
				'parameters' => array(),
			),
			array(
				array(
					'startDateTime' => 'not-a-datetime',
					'endDateTime' => new DateTime(),
					'capacity' => 1,
				)
			),
			'expected to be DateTime object, string given'
		);
	}

	/**
	 * @param array $requestData
	 * @param array $responseData
	 * @param string $error
	 */
	private function checkException(array $requestData, array $responseData, $error)
	{
		try {
			$request = $this->getRequestMock($requestData);
			$method = new CheckSlots(function () use ($responseData) {
				return $responseData;
			});
			$method->call($request);
			$this->fail('Expected exception to be thrown');

		} catch (LogicException $e) {
			$this->assertContains($error, $e->getMessage());
		}
	}

}
