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
		try {
			Validator::checkStructure($data, array(
				array(
					'startDateTime' => Validator::TYPE_DATE_TIME,
					'endDateTime' => Validator::TYPE_DATE_TIME,
					'capacity' => Validator::TYPE_INT,
				),
			));
		} catch (ValidatorException $e) {
			throw new MethodException('Bad method output: ' . $e->getMessage(), $e);
		}

		return array_map(function ($slot) {
			return array(
				'startDateTime' => $slot['startDateTime']->format(DateTime::W3C),
				'endDateTime' => $slot['endDateTime']->format(DateTime::W3C),
				'capacity' => $slot['capacity'],
			);
		}, $data);
	}

}
