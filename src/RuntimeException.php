<?php

namespace HelePartnerSyncApi;

use Exception;
use HelePartnerSyncApi\Exception as HeleException;
use RuntimeException as BaseRuntimeException;

abstract class RuntimeException extends BaseRuntimeException implements HeleException
{

	public function __construct($message, Exception $previous = null)
	{
		parent::__construct($message, 0, $previous);
	}

}
