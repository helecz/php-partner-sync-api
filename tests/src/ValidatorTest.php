<?php

namespace HelePartnerSyncApi;

use DateTime;
use PHPUnit_Framework_TestCase;
use stdClass;

class ValidatorTest extends PHPUnit_Framework_TestCase
{

	public function testValidExamples()
	{
		$now = new DateTime();
		Validator::checkNull(null);
		Validator::checkString('true');
		Validator::checkString('0');
		Validator::checkString('1.9');
		Validator::checkString('array');
		Validator::checkInt(121);
		Validator::checkInt(-78);
		Validator::checkInt(0);
		Validator::checkDateTime($now);
		Validator::checkDateTimeString($now->format(DATE_W3C));
		$this->assertTrue(true);
	}

	/**
	 * @return mixed[][]
	 */
	public function provideInvalidNulls()
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
	 * @dataProvider provideInvalidNulls
	 * @expectedException \HelePartnerSyncApi\ValidatorException
	 * @expectedExceptionMessage Null expected
	 */
	public function testCheckInvalidNulls($value)
	{
		Validator::checkNull($value);
	}

	/**
	 * @return mixed[][]
	 */
	public function provideInvalidStrings()
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
	 * @param mixed $value
	 *
	 * @dataProvider provideInvalidStrings
	 * @expectedException \HelePartnerSyncApi\ValidatorException
	 * @expectedExceptionMessage String expected
	 */
	public function testCheckInvalidStrings($value)
	{
		Validator::checkString($value);
	}

	/**
	 * @return mixed[][]
	 */
	public function provideInvalidInts()
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
	 * @param mixed $value
	 *
	 * @dataProvider provideInvalidInts
	 * @expectedException \HelePartnerSyncApi\ValidatorException
	 * @expectedExceptionMessage Int expected
	 */
	public function testCheckInvalidIntegers($value)
	{
		Validator::checkInt($value);
	}

	public function testCheckValidStructures()
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

	/**
	 * @expectedException \HelePartnerSyncApi\ValidatorException
	 * @expectedExceptionMessage Invalid type in [foo]: Int expected, array given.
	 */
	public function testCheckInvalidStructuresBasic()
	{
		Validator::checkStructure(array(
			'moo' => 0,
			'foo' => array(),
		), array(
			'moo' => Validator::TYPE_INT,
			'foo' => Validator::TYPE_INT,
		));
	}

	/**
	 * @expectedException \HelePartnerSyncApi\ValidatorException
	 * @expectedExceptionMessage Invalid type in [foo][bar]: Int expected, string (moo) given.
	 */
	public function testCheckInvalidStructuresDeep()
	{
		Validator::checkStructure(array(
			'foo' => array(
				'bar' => 'moo',
			),
		), array(
			'foo' => array(
				'bar' => Validator::TYPE_INT,
			),
		));
	}

	/**
	 * @expectedException \HelePartnerSyncApi\ValidatorException
	 * @expectedExceptionMessage Missing keys (bar, foo) in []
	 */
	public function testCheckInvalidStructuresMissingKeys()
	{
		Validator::checkStructure(array(), array(
			'bar' => Validator::TYPE_INT,
			'foo' => Validator::TYPE_INT,
		));
	}

	/**
	 * @expectedException \HelePartnerSyncApi\ValidatorException
	 * @expectedExceptionMessage Unknown keys (foo, bar) in []
	 */
	public function testCheckInvalidStructuresUnknownKeys()
	{
		Validator::checkStructure(array(
			'foo' => 'foo',
			'bar' => 'bar',
		), array(
			'moo' => Validator::TYPE_INT,
		));
	}

}
