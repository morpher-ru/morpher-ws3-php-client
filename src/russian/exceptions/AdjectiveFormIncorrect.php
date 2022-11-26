<?php

namespace Morpher\Ws3Client\Russian\Exceptions;

use InvalidArgumentException;

class AdjectiveFormIncorrect extends InvalidArgumentException
{
	public function __construct(string $message = 'Нарушены требования к входному прилагательному.', int $code = 0)
	{
		parent::__construct($message, $code);
	}
}
