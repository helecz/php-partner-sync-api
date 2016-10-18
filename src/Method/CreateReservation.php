<?php

namespace HelePartnerSyncApi\Method;

use DateTime;
use HelePartnerSyncApi\Validator;

class CreateReservation extends Method
{

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'createReservation';
	}

	/**
	 * @param mixed $data
	 * @return null
	 */
	protected function parseResponseData($data)
	{
		Validator::checkStructure($data, Validator::TYPE_NULL);

		return null;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	protected function parseRequestData($data)
	{
		Validator::checkStructure($data, array(
			'startDateTime' => Validator::TYPE_DATE_TIME_STRING,
			'endDateTime' => Validator::TYPE_DATE_TIME_STRING,
			'quantity' => Validator::TYPE_INT,
			'parameters' => Validator::TYPE_ARRAY,
		));

		return array(
			DateTime::createFromFormat(DateTime::W3C, $data['startDateTime']),
			DateTime::createFromFormat(DateTime::W3C, $data['endDateTime']),
			(int) $data['quantity'],
			(array) $data['parameters'],
		);
	}

}
