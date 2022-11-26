<?php

namespace Morpher\Ws3Client\Exceptions;

use Exception;

class TokenNotFound extends Exception
{
	public function __construct(string $message = 'Данный токен не найден.', int $code = 0)
	{
		parent::__construct($message, $code);
	}
}
