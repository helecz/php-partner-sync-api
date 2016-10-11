<?php

namespace HelePartnerSyncApi;

abstract class Response
{

	public function render()
	{
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

}