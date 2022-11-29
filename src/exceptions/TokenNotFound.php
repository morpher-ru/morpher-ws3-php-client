<?php

namespace Morpher\Ws3Client;

class TokenNotFound extends \Exception
{
	function __construct(string $message = 'Данный токен не найден.', int $code = 0)
	{
		parent::__construct($message, $code);
	}
}