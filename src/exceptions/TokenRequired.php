<?php

namespace Morpher\Ws3Client\Exceptions;

use Exception;

class TokenRequired extends Exception
{
	public function __construct(string $message = 'Требуется указать токен.', int $code = 0)
	{
		parent::__construct($message, $code);
	}
}
