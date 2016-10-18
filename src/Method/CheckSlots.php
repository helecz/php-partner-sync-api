<?php

namespace HelePartnerSyncApi\Method;

use DateTime;
use HelePartnerSyncApi\Validator;
use HelePartnerSyncApi\ValidatorException;

class CheckSlots extends Method
{

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'checkSlots';
	}

	/**
	 * @param array $data
	 * @return array
	 */
	protected function parseRequestData($data)
	{
		try {
			Validator::checkStructure($data, array(
				'date' => Validator::TYPE_DATE_TIME_STRING,
				'parameters' => Validator::TYPE_ARRAY,
			));
		} catch (ValidatorException $e) {
			throw new MethodException('Bad method input: ' . $e->getMessage(), $e);
		}

		return array(
			new DateTime($data['date']),
			$data['parameters'],
		);
	}

	/**
	 * @param mixed $data
	 * @return array
	 */
	protected function parseResponseData($data)
	{
		$exceptionPrefix = 'Bad method output: ';

		try {
			Validator::checkStructure($data, array(
				array(
					'startDateTime' => Validator::TYPE_DATE_TIME,
					'endDateTime' => Validator::TYPE_DATE_TIME,
					'capacity' => Validator::TYPE_INT,
				),
			));
		} catch (ValidatorException $e) {
			throw new MethodException($exceptionPrefix . $e->getMessage(), $e);
		}

		$result = array();

		foreach ($data as $index => $slot) {
			$whichSlot = sprintf('(slot #%d)', $index + 1);
			$startDateTime = $slot['startDateTime']->format(DateTime::W3C);
			$endDateTime = $slot['endDateTime']->format(DateTime::W3C);
			$capacity = $slot['capacity'];

			if ($startDateTime >= $endDateTime) {
				throw new MethodException(sprintf('%sSlot startDateTime (%s) must be before endDateTime (%s) %s', $exceptionPrefix, $startDateTime, $endDateTime, $whichSlot));
			}

			if ($capacity < 0) {
				throw new MethodException(sprintf('%sSlot capacity (%s) must be non-negative %s', $exceptionPrefix, $capacity, $whichSlot));
			}

			$result[] = array(
				'startDateTime' => $startDateTime,
				'endDateTime' => $endDateTime,
				'capacity' => $capacity,
			);
		}

		return $result;
	}

}
