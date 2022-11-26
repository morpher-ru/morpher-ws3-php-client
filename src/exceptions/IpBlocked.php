<?php

namespace Morpher\Ws3Client\Exceptions;

use Exception;

class IpBlocked extends Exception
{
	public function __construct(string $message = 'IP заблокирован.', int $code = 0)
	{
		parent::__construct($message, $code);
	}
}
