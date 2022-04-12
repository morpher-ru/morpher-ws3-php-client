<?php
namespace Morpher\Ws3Client;

require_once __DIR__."/../vendor/autoload.php";


class HttpRequest
{
	public readonly ?string $Endpoint;
	public readonly ?string $Method; // GET / POST / DELETE...
	public readonly ?array $Headers;
	public readonly ?array $QueryParameters;

	function __construct($Endpoint='',$Method='GET',$Headers=[],$QueryParameters=[])
	{
		$this->Endpoint=$Endpoint;
		$this->Method=$Method;
		$this->Headers=$Headers;
		$this->QueryParameters=$QueryParameters;
	}
}