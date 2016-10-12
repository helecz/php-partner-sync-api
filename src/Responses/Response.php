<?php

namespace HelePartnerSyncApi\Responses;

abstract class Response
{

	public function render()
	{
		$this->sendHttpCode();

		$response = array(
			'success' => $this->isSuccessful(),
			'message' => $this->getMessage(),
			'data' => $this->getData(),
		);

		echo json_encode($response);
	}

	/**
	 * @return array
	 */
	abstract public function getData();

	/**
	 * @return bool
	 */
	abstract public function isSuccessful();

	/**
	 * @return string
	 */
	abstract public function getMessage();

	private function sendHttpCode()
	{
		$httpCode = $this->isSuccessful() ? 200 : 500;

		if (function_exists('http_response_code')) {
			http_response_code($httpCode);
		} else {
			header('HTTP/1.1 ' . $httpCode);
		}
	}

}
