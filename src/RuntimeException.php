<?php

namespace HelePartnerSyncApi;

use Exception as BaseException;
use HelePartnerSyncApi\Exception as HeleException;
use RuntimeException as BaseRuntimeException;

abstract class RuntimeException extends BaseRuntimeException implements HeleException
{

	public function __construct($message, BaseException $previous = null)
	{
		parent::__construct($message, 0, $previous);
	}

}
