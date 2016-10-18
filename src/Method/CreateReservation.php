<?php

namespace HelePartnerSyncApi\Method;

use DateTime;
use HelePartnerSyncApi\Validator;
use HelePartnerSyncApi\ValidatorException;

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
	protected function parseRequestData($data)
	{
		try {
			Validator::checkStructure($data, array(
				'startDateTime' => Validator::TYPE_DATE_TIME_STRING,
				'endDateTime' => Validator::TYPE_DATE_TIME_STRING,
				'quantity' => Validator::TYPE_INT,
				'parameters' => Validator::TYPE_ARRAY,
			));
		} catch (ValidatorException $e) {
			throw new MethodException('Bad method input: ' . $e->getMessage(), $e);
		}

		return array(
			DateTime::createFromFormat(DateTime::W3C, $data['startDateTime']),
			DateTime::createFromFormat(DateTime::W3C, $data['endDateTime']),
			$data['quantity'],
			$data['parameters'],
		);
	}

	/**
	 * @param mixed $data
	 * @return null
	 */
	protected function parseResponseData($data)
	{
		try {
			Validator::checkNull($data);
		} catch (ValidatorException $e) {
			throw new MethodException('Bad method output: ' . $e->getMessage(), $e);
		}

		return array();
	}

}
