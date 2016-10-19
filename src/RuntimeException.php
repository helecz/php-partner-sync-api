<?php

namespace HelePartnerSyncApi;

use Exception;
use RuntimeException as InternalRuntimeException;

abstract class RuntimeException extends InternalRuntimeException implements HeleException
{

	public function __construct($message, Exception $previous = null)
	{
		parent::__construct($message, 0, $previous);
	}

}
