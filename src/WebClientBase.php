<?php
namespace Morpher\Ws3Client;

require_once __DIR__."/../vendor/autoload.php";

abstract class  WebClientBase
{
	abstract public function get_request($endpoint, $params = NULL);
}