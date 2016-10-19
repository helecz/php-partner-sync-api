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
		Validator::checkStructure($data, array(
			'date' => Validator::TYPE_DATE_TIME_STRING,
			'parameters' => Validator::TYPE_ARRAY,
		));

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
		Validator::checkStructure($data, array(
			array(
				'startDateTime' => Validator::TYPE_DATE_TIME,
				'endDateTime' => Validator::TYPE_DATE_TIME,
				'capacity' => Validator::TYPE_INT,
			),
		));

		$result = array();

		foreach ($data as $index => $slot) {
			$whichSlot = sprintf('(slot #%d)', $index + 1);
			$startDateTime = $slot['startDateTime']->format(DateTime::W3C);
			$endDateTime = $slot['endDateTime']->format(DateTime::W3C);
			$capacity = $slot['capacity'];

			if ($startDateTime >= $endDateTime) {
				throw new ValidatorException(sprintf('Slot startDateTime (%s) must be before endDateTime (%s) %s', $startDateTime, $endDateTime, $whichSlot));
			}

			if ($capacity < 0) {
				throw new ValidatorException(sprintf('Slot capacity (%s) must be non-negative %s', $capacity, $whichSlot));
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
