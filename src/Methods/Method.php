<?php

namespace HelePartnerSyncApi\Methods;

use Closure;

abstract class Method
{

	/**
	 * @var Closure|null
	 */
	private $callback;

	/**
	 * @param Closure $callback
	 */
	public function __construct(Closure $callback)
	{
		$this->callback = $callback;
	}

	/**
	 * @return Closure|null
	 */
	public function getCallback()
	{
		return $this->callback;
	}

	/**
	 * @return string
	 */
	abstract public function getName();

	/**
	 * @param array $data
	 * @return array
	 */
	abstract public function validateResponseData(array $data);

	/**
	 * @param array $data
	 * @return array
	 */
	abstract public function parseRequestData(array $data);

}