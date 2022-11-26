<?php

namespace Morpher\Ws3Client\Exceptions;

class InvalidServerResponse extends \Exception
{
	/**
	 * @readonly
	 */
	public string $response;

	public function __construct(string $message = "", string $response = '')
	{
		parent::__construct($message);
		$this->response = $response;
	}
}
