<?php

namespace HelePartnerSyncApi\Method;

use Closure;
use HelePartnerSyncApi\Request\Request;
use HelePartnerSyncApi\ValidatorException;

abstract class Method
{

	/**
	 * @var Closure
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
	 * @param Request $request
	 * @return array
	 */
	public function call(Request $request)
	{
		try {
			$requestData = $this->constructRequestData($request->getData());
		} catch (ValidatorException $e) {
			throw new MethodException('Invalid request data from server: ' . $e->getMessage(), $e);
		}

		try {
			return $this->constructResponseData(call_user_func_array(
				$this->callback,
				$requestData
			));
		} catch (ValidatorException $e) {
			throw new MethodException(sprintf('Invalid data returned from callback of %s method: %s', $this->getName(), $e->getMessage()), $e);
		}
	}

	/**
	 * @return string
	 */
	abstract public function getName();

	/**
	 * @param array $data
	 * @return array
	 */
	abstract protected function constructRequestData($data);

	/**
	 * @param mixed $data
	 * @return array
	 */
	abstract protected function constructResponseData($data);

}
