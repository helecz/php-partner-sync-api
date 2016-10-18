<?php

namespace HelePartnerSyncApi\Request;

interface RequestFactory
{

	/**
	 * @return Request
	 */
	public function createRequest();

}
