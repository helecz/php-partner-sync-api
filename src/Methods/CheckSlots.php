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
	protected function parseResponseData($data)
	{
		$this->checkArray($data);

		$result = array();

		foreach ($data as $slot) {
			$this->checkArray($slot);
			$this->checkExistence($slot, array('startDateTime', 'endDateTime', 'capacity'));
			$this->checkDateTime($slot, 'startDateTime');
			$this->checkDateTime($slot, 'endDateTime');
			$this->checkInt($slot, 'capacity');

			$result[] = array(
				'startDateTime' => $slot['startDateTime']->format(DateTime::W3C),
				'endDateTime' => $slot['endDateTime']->format(DateTime::W3C),
				'capacity' => $slot['capacity']
			);
		}

		return $result;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	protected function parseRequestData($data)
	{
		$this->checkArray($data);
		$this->checkExistence($data, array('date', 'parameters'));

		return array(
			new DateTime($data['date']),
			$data['parameters'],
		);
	}

	private function checkArray($data)
	{
		if (!is_array($data)) {
			throw new LogicException('Expected array, got ' . $this->getType($data));
		}
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
			unset($data[$field]);
		}

		if (count($data) > 0) {
			throw new LogicException(sprintf('Unexpected fields: %s', implode(', ', array_keys($data))));
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
