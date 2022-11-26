<?php

namespace Morpher\Ws3Client\Exceptions;

use Exception;

class TokenIncorrectFormat extends Exception
{
	public function __construct(string $message = 'Неверный формат токена.', int $code = 0)
	{
		parent::__construct($message, $code);
	}
}
