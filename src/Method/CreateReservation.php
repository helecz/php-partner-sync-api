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
	 * @param array $data
	 * @return array
	 */
	protected function constructRequestData($data)
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
			$data['quantity'],
			$data['parameters'],
		);
	}

	/**
	 * @param mixed $data
	 * @return array
	 */
	protected function constructResponseData($data)
	{
		Validator::checkNull($data);

		return array();
	}

}
