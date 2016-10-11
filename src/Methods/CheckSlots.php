<?php

namespace HelePartnerSyncApi\Methods;

use DateTime;
use LogicException;

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
	public function validateResponseData(array $data)
	{
		// TODO
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public function parseRequestData(array $data)
	{
		if (!isset($data['date'])) {
			throw new LogicException('Missing date field in data of ' . $this->getName() . ' method');

		} elseif (!preg_match('~[0-9]{4}-[0-9]{2}-[0-9]{2}~', $data['date'])) {
			throw new LogicException('Invalid date field in data of ' . $this->getName() . ' method');
		}

		return array(
			new DateTime($data['date']),
			isset($data['parameters']) ? (array)$data['parameters'] : array(),
		);
	}

}
