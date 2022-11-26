<?php

namespace Morpher\Ws3Client\Exceptions;

use Exception;

class RequestsDailyLimit extends Exception
{
	public function __construct(string $message = 'Превышен лимит на количество запросов в сутки. Перейдите на следующий тарифный план.',
		int $code = 0)
	{
		parent::__construct($message, $code);
	}
}
