<?php

namespace HelePartnerSyncApi\Method;

use DateTime;
use HelePartnerSyncApi\ValidationException;
use HelePartnerSyncApi\Validator;

class GetSlots extends Method
{

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'getSlots';
	}

	/**
	 * @param array $data
	 * @return array
	 */
	protected function constructRequestData($data)
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
	 * @param array $requestData
	 * @param mixed $responseData
	 * @return array
	 */
	protected function constructResponseData(array $requestData, $responseData)
	{
		Validator::checkStructure($responseData, array(
			array(
				'startDateTime' => Validator::TYPE_DATE_TIME,
				'endDateTime' => Validator::TYPE_DATE_TIME,
				'capacity' => Validator::TYPE_INT,
			),
		));

		$result = array();
		$requestedDate = $requestData[0]->format('Y-m-d');

		foreach ($responseData as $index => $slot) {
			$whichSlot = sprintf('(slot #%d)', $index + 1);
			$startDateTime = $slot['startDateTime']->format(DateTime::W3C);
			$endDateTime = $slot['endDateTime']->format(DateTime::W3C);
			$startDate = $slot['startDateTime']->format('Y-m-d');
			$capacity = $slot['capacity'];

			if ($startDateTime >= $endDateTime) {
				throw new ValidationException(sprintf('Slot startDateTime (%s) must be before endDateTime (%s) %s', $startDateTime, $endDateTime, $whichSlot));
			}

			if ($requestedDate !== $startDate) {
				throw new ValidationException(sprintf('Slot startDateTime (%s) does not match requested day (%s) %s', $startDate, $requestedDate, $whichSlot));
			}

			if ($capacity < 0) {
				throw new ValidationException(sprintf('Slot capacity (%d) must be non-negative %s', $capacity, $whichSlot));
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
