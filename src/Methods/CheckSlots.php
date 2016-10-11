<?php

namespace HelePartnerSyncApi\Methods;

use DateTime;
use DateTimeInterface;
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
	public function parseResponseData($data)
	{
		if (!is_array($data)) {
			throw new LogicException('Expected array to be returned from method ' . $this->getName());
		}

		$result = array();

		foreach ($data as $slot) {
			$this->checkExistence($slot, array('startDateTime', 'endDateTime', 'capacity'));
			$this->checkDateTime($slot, 'startDateTime');
			$this->checkDateTime($slot, 'endDateTime');
			$this->checkInt($slot, 'capacity');

			$result[] = array(
				$slot['startDateTime']->format(DateTime::W3C),
				$slot['endDateTime']->format(DateTime::W3C),
				$slot['capacity']
			);
		}

		return $result;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public function parseRequestData(array $data)
	{
		return array(
			new DateTime($data['date']),
			$data['parameters'],
		);
	}

	/**
	 * @param array $data
	 * @param string[] $fields
	 */
	protected function checkExistence(array $data, array $fields)
	{
		foreach ($fields as $field) {
			if (!isset($data[$field])) {
				throw new LogicException(sprintf('Missing "%s" field in response of %s method', $field, $this->getName()));
			}
		}
	}

	/**
	 * @param array $data
	 * @param string $field
	 */
	private function checkDateTime(array $data, $field)
	{
		if (!$data[$field] instanceof DateTime && !$data[$field] instanceof DateTimeInterface) {
			throw new LogicException(sprintf('Field "%s" in response of %s method is expected to be DateTime object, %s given', $field, $this->getName(), $this->getType($data[$field])));
		}
	}

	/**
	 * @param array $data
	 * @param string $field
	 */
	private function checkInt(array $data, $field)
	{
		if (!is_int($data[$field])) {
			throw new LogicException(sprintf('Field "%s" in response of %s method is expected to be integer, %s given', $field, $this->getName(), $this->getType($data[$field])));
		}
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	private function getType($value)
	{
		return is_object($value) ? get_class($value) : gettype($value);
	}

}
