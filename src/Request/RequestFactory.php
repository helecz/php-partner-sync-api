<?php

namespace HelePartnerSyncApi\Request;

interface RequestFactory
{

	/**
	 * @param string $body
	 * @param string[] $headers
	 * @return Request
	 */
	public function createRequest($body, array $headers);

}
