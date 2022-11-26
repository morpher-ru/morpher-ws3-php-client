<?php

namespace Morpher\Ws3Client\Russian\Exceptions;

use InvalidArgumentException;

class IncorrectDateFormat extends InvalidArgumentException
{
	public function __construct(string $message = 'Дата указана в некорректном формате. Ожидается YYYY-MM-DD.', int $code = 0)
	{
		parent::__construct($message, $code);
	}
}
