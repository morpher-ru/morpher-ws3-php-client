<?php
namespace Morpher\Ws3Client\Russian;

require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/../WebClientBase.php";
require_once "DeclensionResult.php";
require_once __DIR__."/../HttpRequest.php";

use Morpher\Ws3Client\WebClientBase;
use Morpher\Ws3Client\HttpRequest;
use Morpher\Ws3Client\Russian\DeclensionResult;
class Client
{
	private $webClient;
	
	public function __construct(WebClientBase $webClient)
	{
		$this->webClient=$webClient;
	}
	
	public function Parse(string $lemma)
	{
		if (trim($lemma)=='') throw new \InvalidArgumentException("пустая строка");
		
		$request=new HttpRequest("/russian/declension",'GET',  
			[
				'Accept'=> 'application/json',
				'Authorization'=> 'Basic '.$this->webClient->getToken()
			],  
			['s' => $lemma]  
		);

		$result_raw=$this->webClient->send($request);

		$result = json_decode($result_raw,true);
		//
		//parse result

		$result['И']=$lemma;
		$declensionResult = new DeclensionResult($result);


		return $declensionResult;
	}
	
}