<?php
namespace Morpher\Ws3Client;

require_once __DIR__."/../vendor/autoload.php";
require_once "HttpRequest.php";

abstract class  WebClientBase
{
	abstract public function send(HttpRequest $request): string;

	abstract public function getToken(): string;
}

