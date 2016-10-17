<?php

namespace HelePartnerSyncApi\Methods;

use DateTime;

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
	protected function parseResponseData($data)
	{
		return array();
	}

	/**
	 * @param array $data
	 * @return array
	 */
	protected function parseRequestData($data)
	{
		return array(
			DateTime::createFromFormat(DateTime::W3C, $data['startDateTime']),
			DateTime::createFromFormat(DateTime::W3C, $data['endDateTime']),
			(int) $data['quantity'],
			(array) $data['parameters'],
		);
	}

}
