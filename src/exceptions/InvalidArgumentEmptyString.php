<?php

namespace Morpher\Ws3Client\Exceptions;

use InvalidArgumentException;

class InvalidArgumentEmptyString extends InvalidArgumentException
{
	public function __construct(string $message = 'Передана пустая строка.', int $code = 0)
	{
		parent::__construct($message, $code);
	}
}
