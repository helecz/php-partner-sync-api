<?php

namespace HelePartnerSyncApi\Method;

use Closure;
use HelePartnerSyncApi\Request\Request;
use HelePartnerSyncApi\ValidationException;

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
		} catch (ValidationException $e) {
			throw new MethodException('Invalid request data from server: ' . $e->getMessage(), $e);
		}

		try {
			return $this->constructResponseData($requestData, call_user_func_array(
				$this->callback,
				$requestData
			));
		} catch (ValidationException $e) {
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
	 * @param array $requestData Return value of constructRequestData
	 * @param mixed $responseData
	 * @return array
	 */
	abstract protected function constructResponseData(array $requestData, $responseData);

}
