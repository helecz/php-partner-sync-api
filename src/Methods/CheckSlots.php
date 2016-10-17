<?php

namespace HelePartnerSyncApi\Methods;

use DateTime;
use HelePartnerSyncApi\Validator;

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
	protected function parseResponseData($data)
	{
		Validator::checkStructure($data, array(
			array(
				'startDateTime' => Validator::TYPE_DATE_TIME,
				'endDateTime' => Validator::TYPE_DATE_TIME,
				'capacity' => Validator::TYPE_INT,
			),
		));

		return array_map(function ($slot) {
			return array(
				'startDateTime' => $slot['startDateTime']->format(DateTime::W3C),
				'endDateTime' => $slot['endDateTime']->format(DateTime::W3C),
				'capacity' => $slot['capacity'],
			);
		}, $data);
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

}
