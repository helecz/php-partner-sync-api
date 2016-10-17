<?php

namespace HelePartnerSyncApi;

use DateTime;
use DateTimeInterface;
use LogicException;

class Validator
{

	const TYPE_NULL = 'null';
	const TYPE_ARRAY = 'array';
	const TYPE_INT = 'int';
	const TYPE_STRING = 'string';
	const TYPE_DATE_TIME = 'datetime';
	const TYPE_DATE_TIME_STRING = 'datetimestring';

	private static function getCheckers()
	{
		$that = new self();

		static $checkers = array();

		$checkers = array(
			self::TYPE_NULL => function ($value) use ($that) {
				$that::checkNull($value);
			},
			self::TYPE_ARRAY => function ($value) use ($that) {
				$that::checkArray($value);
			},
			self::TYPE_INT => function ($value) use ($that) {
				$that::checkInt($value);
			},
			self::TYPE_STRING => function ($value) use ($that) {
				$that::checkString($value);
			},
			self::TYPE_DATE_TIME => function ($value) use ($that) {
				$that::checkDateTime($value);
			},
			self::TYPE_DATE_TIME_STRING => function ($value) use ($that) {
				$that::checkDateTimeString($value);
			},
		);

		return $checkers;
	}

	public static function checkStructure($value, $type)
	{
		$checkers = self::getCheckers();

		if (is_array($type)) {
			self::checkArray($value);
			self::checkArrayStructure($value, $type);
			return;
		}

		if (!array_key_exists($type, $checkers)) {
			throw new LogicException(sprintf('Unknown type `%s`', $type));
		}

		$checker = $checkers[$type];
		$checker($value);
	}

	private static function checkArrayStructure($data, $structure)
	{
		self::checkArray($data);
		self::checkArray($structure);

		$listStructure = self::isList($structure);
		if (!$listStructure) {
			if (count($data) > 0 && self::isList($data)) {
				throw new LogicException('Nested data must not be list');
			}

			$diff = array_diff_key($data, $structure);
			if (count($diff) > 0) {
				throw new LogicException(sprintf('Unknown data keys: `%s`', implode(', ', array_keys($diff))));
			}

			$diff = array_diff_key($structure, $data);
			if (count($diff) > 0) {
				throw new LogicException(sprintf('Missing keys in data: `%s`', implode(', ', array_keys($diff))));
			}
		}

		foreach ($data as $key => $value) {
			self::checkStructure($value, $listStructure ? $structure[0] : $structure[$key]);
		}
	}

	/**
	 * @param mixed $value
	 */
	public static function checkNull($value)
	{
		if (!is_null($value)) {
			self::throwException('Null', $value);
		}
	}

	/**
	 * @param mixed $value
	 */
	public static function checkArray($value)
	{
		if (!is_array($value)) {
			self::throwException('Array', $value);
		}
	}

	/**
	 * @param mixed $value
	 */
	public static function checkDateTime($value)
	{
		if (!$value instanceof DateTime && !$value instanceof DateTimeInterface) {
			self::throwException('DateTime', $value);
		}
	}

	/**
	 * @param mixed $value
	 */
	public static function checkDateTimeString($value)
	{
		if (!is_string($value)) {
			self::throwException('DateTime', $value);
		}

		$dateTime = DateTime::createFromFormat(DateTime::W3C, $value);
		if ($dateTime === false) {
			self::throwException('DateTime', $value);
		}
	}

	/**
	 * @param mixed $value
	 */
	public static function checkInt($value)
	{
		if (!is_int($value)) {
			self::throwException('Int', $value);
		}
	}

	/**
	 * @param mixed $value
	 */
	public static function checkString($value)
	{
		if (!is_string($value)) {
			self::throwException('String', $value);
		}
	}

	/**
	 * @param string $type
	 * @param mixed $value
	 * @internal
	 */
	private static function throwException($type, $value)
	{
		if (is_scalar($value)) {
			throw new LogicException(sprintf('%s expected, %s (%s) given.', $type, self::getType($value), $value));
		} else {
			throw new LogicException(sprintf('%s expected, %s given.', $type, self::getType($value)));
		}
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	private static function getType($value)
	{
		return is_object($value) ? get_class($value) : gettype($value);
	}

	/**
	 * @param mixed $value
	 * @return bool
	 */
	private static function isList($value)
	{
		return is_array($value) && (count($value) === 0 || array_keys($value) === range(0, count($value) - 1));
	}

}
