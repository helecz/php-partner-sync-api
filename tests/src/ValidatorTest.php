<?php

namespace HelePartnerSyncApi;

use PHPUnit_Framework_TestCase;
use stdClass;

class ValidatorTest extends PHPUnit_Framework_TestCase
{

	public function testNull()
	{
		Validator::checkNull(null);
		$this->assertTrue(true);
	}

	/**
	 * @return mixed[][]
	 */
	public function invalidNulls()
	{
		return array(
			array(true),
			array(false),
			array(0),
			array(0.0),
			array(array()),
		);
	}

	/**
	 * @param mixed $value
	 *
	 * @dataProvider invalidNulls
	 * @expectedException \LogicException
	 * @expectedExceptionMessage Null expected
	 */
	public function testCheckNullThrowsExceptionForInvalidNulls($value)
	{
		Validator::checkNull($value);
	}

	/**
	 * @return mixed[][]
	 */
	public function invalidStrings()
	{
		return array(
			array(true),
			array(0.5),
			array(1212),
			array(null),
			array(array()),
		);
	}

	/**
	 * @return mixed[][]
	 */
	public function validStrings()
	{
		return array(
			array('sfdsfsdfsd'),
			array('444545'),
			array('true'),
		);
	}

	/**
	 * @param mixed $value
	 *
	 * @dataProvider validStrings
	 */
	public function testCheckString($value)
	{
		Validator::checkString($value);
		$this->assertTrue(true);
	}

	/**
	 * @param mixed $value
	 *
	 * @dataProvider invalidStrings
	 * @expectedException \LogicException
	 * @expectedExceptionMessage String expected
	 */
	public function testCheckStringThrowsExceptionForInvalidStrings($value)
	{
		Validator::checkString($value);
	}

	/**
	 * @return mixed[][]
	 */
	public function invalidInts()
	{
		return array(
			array('1212'),
			array(true),
			array(0.5),
			array(null),
			array(array()),
			array(new stdClass()),
		);
	}

	/**
	 * @return mixed[][]
	 */
	public function validInts()
	{
		return array(
			array(12122),
			array(-7878787),
		);
	}

	/**
	 * @param mixed $value
	 *
	 * @dataProvider validInts
	 */
	public function testCheckInt($value)
	{
		Validator::checkInt($value);
		$this->assertTrue(true);
	}

	/**
	 * @param mixed $value
	 *
	 * @dataProvider invalidInts
	 * @expectedException \LogicException
	 * @expectedExceptionMessage Int expected
	 */
	public function testCheckIntegerThrowsExceptionForInvalidIntegers($value)
	{
		Validator::checkInt($value);
	}

	public function testSuccess()
	{
		Validator::checkStructure(array(
			'key' => 123,
			'data' => array(
				'text' => 'abc',
			),
		), array(
			'key' => Validator::TYPE_INT,
			'data' => array(
				'text' => Validator::TYPE_STRING,
			),
		));

		Validator::checkStructure(array(
			array(
				'from' => 12,
				'to' => 16,
			),
			array(
				'from' => 17,
				'to' => 22,
			),
		), array(
			array(
				'from' => Validator::TYPE_INT,
				'to' => Validator::TYPE_INT,
			),
		));

		$this->assertTrue(true);
	}

}
