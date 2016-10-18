<?php

namespace HelePartnerSyncApi;

use DateTime;
use DateTimeInterface;

class Validator
{

	const TYPE_NULL = 'null';
	const TYPE_ARRAY = 'array';
	const TYPE_INT = 'int';
	const TYPE_STRING = 'string';
	const TYPE_DATE_TIME = 'datetime';
	const TYPE_DATE_TIME_STRING = 'datetimestring';

	/**
	 * @param mixed $value
	 * @param string $type self::TYPE_*
	 */
	public static function checkType($value, $type)
	{
		switch ($type) {
			case self::TYPE_NULL:
				self::checkNull($value);
				break;

			case self::TYPE_ARRAY:
				self::checkArray($value);
				break;

			case self::TYPE_INT:
				self::checkInt($value);
				break;

			case self::TYPE_STRING:
				self::checkString($value);
				break;

			case self::TYPE_DATE_TIME:
				self::checkDateTime($value);
				break;

			case self::TYPE_DATE_TIME_STRING:
				self::checkDateTimeString($value);
				break;

			default:
				throw new ValidatorException(sprintf('Unknown type %s to validate', $type));
		}
	}

	public static function checkStructure($data, array $structure, array $path = array())
	{
		if (count($structure) === 0) {
			throw new ValidatorException(sprintf('Cannot validate against empty structure in %s', self::getStructurePath($path)));
		}

		self::checkArray($data);
		self::checkArray($structure);

		$listCheck = self::isList($structure);
		if (!$listCheck) {
			if (count($data) > 0 && self::isList($data)) {
				throw new ValidatorException(sprintf('Unexpected list structure (%s elements found) in %s', count($data), self::getStructurePath($path)));
			}

			$diff = array_diff_key($data, $structure);
			if (count($diff) > 0) {
				throw new ValidatorException(sprintf('Unknown keys (%s) in %s', implode(', ', array_keys($diff)), self::getStructurePath($path)));
			}

			$diff = array_diff_key($structure, $data);
			if (count($diff) > 0) {
				throw new ValidatorException(sprintf('Missing keys (%s) in %s', implode(', ', array_keys($diff)), self::getStructurePath($path)));
			}
		}

		foreach ($data as $key => $value) {
			$newStructure = $listCheck ? $structure[0] : $structure[$key];

			if (is_array($value) && is_array($newStructure)) {
				$newPath = array_merge($path, array($key));
				self::checkStructure($value, $newStructure, $newPath);
			} else {
				try {
					self::checkType($value, $newStructure);
				} catch (ValidatorException $e) {
					throw new ValidatorException(sprintf('Invalid type in %s: %s', self::getStructurePath($path, $key), $e->getMessage()), $e);
				}
			}
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
		self::checkString($value);

		$dateTime = DateTime::createFromFormat(DateTime::W3C, $value);
		if ($dateTime === false) {
			self::throwException('W3C datetime', $value);
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
			throw new ValidatorException(sprintf('%s expected, %s (%s) given.', $type, self::getType($value), $value));
		} else {
			throw new ValidatorException(sprintf('%s expected, %s given.', $type, self::getType($value)));
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

	/**
	 * @param array $path
	 * @param string|null $currentKey
	 * @return string
	 */
	private static function getStructurePath(array $path, $currentKey = null)
	{
		if ($currentKey !== null) {
			$path[] = $currentKey;
		}

		if (count($path) === 0) {
			return 'root';
		} elseif (count($path) === 1) {
			return sprintf('key \'%s\'', $path[0]);
		} else {
			return sprintf('path \'%s\'', implode('.', $path));
		}
	}

}
