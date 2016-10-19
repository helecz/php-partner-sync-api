<?php

namespace HelePartnerSyncApi;

use Exception;
use RuntimeException as InternalRuntimeException;
use Throwable;

abstract class RuntimeException extends InternalRuntimeException implements HeleException
{

	/**
	 * @param string $message
	 * @param Exception|Throwable $previous
	 */
	public function __construct($message, $previous = null)
	{
		parent::__construct($message, 0, $previous);
	}

}
