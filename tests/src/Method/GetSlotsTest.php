<?php

namespace HelePartnerSyncApi\Method;

use DateTime;

class GetSlotsTest extends MethodTestCase
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
		$method = new GetSlots($closure);
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
		$now = new DateTime();
		$before = new DateTime('-1 hour');
		$tomorrow = new DateTime('tomorrow');
		$nowString = $now->format(DateTime::W3C);

		$this->checkException(
			array(),
			array(),
			'Missing keys (date, parameters)'
		);
		$this->checkException(
			array(
				'date' => 'now',
				'parameters' => array(),
			),
			array(
				array(),
			),
			'W3C datetime expected, string (now) given.'
		);
		$this->checkException(
			array(
				'date' => $nowString,
				'parameters' => array(),
			),
			array(
				array(
					'startDateTime' => $now,
				)
			),
			'Missing keys (endDateTime, capacity)'
		);
		$this->checkException(
			array(
				'date' => $nowString,
				'parameters' => array(),
			),
			array(
				array(
					'startDateTime' => $now,
					'endDateTime' => $now,
					'capacity' => 'string',
				)
			),
			'Int expected, string (string) given.'
		);
		$this->checkException(
			array(
				'date' => $nowString,
				'parameters' => array(),
			),
			array(
				array(
					'startDateTime' => 'not-a-datetime',
					'endDateTime' => $now,
					'capacity' => 1,
				)
			),
			'DateTime expected, string (not-a-datetime) given.'
		);
		$this->checkException(
			array(
				'date' => $nowString,
				'parameters' => array(),
			),
			array(
				array(
					'startDateTime' => $before,
					'endDateTime' => $now,
					'capacity' => -1,
				)
			),
			'Slot capacity (-1) must be non-negative (slot #1)'
		);
		$this->checkException(
			array(
				'date' => $nowString,
				'parameters' => array(),
			),
			array(
				array(
					'startDateTime' => $now,
					'endDateTime' => $before,
					'capacity' => 1,
				)
			),
			sprintf('Slot startDateTime (%s) must be before endDateTime (%s) (slot #1)', $nowString, $before->format(DateTime::W3C))
		);
		$this->checkException(
			array(
				'date' => $tomorrow->format(DateTime::W3C),
				'parameters' => array(),
			),
			array(
				array(
					'startDateTime' => $before,
					'endDateTime' => $now,
					'capacity' => 1,
				)
			),
			sprintf('Slot startDateTime (%s) does not match requested day (%s) (slot #1)', $now->format('Y-m-d'), $tomorrow->format('Y-m-d'))
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
			$method = new GetSlots(function () use ($responseData) {
				return $responseData;
			});
			$method->call($request);
			$this->fail(sprintf('Expected exception with "%s" to be thrown', $error));

		} catch (MethodException $e) {
			$this->assertContains($error, $e->getMessage());
		}
	}

}
