<?php
namespace Morpher\Ws3Client\Qazaq;

require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/../WebClient.php";

require_once "DeclensionResult.php";

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Qazaq\DeclensionResult;
class Client
{
	private $webClient;
	
	public function __construct(WebClient $webClient)
	{
		$this->webClient=$webClient;
	}
	
	public function Parse(string $lemma)
	{
		if (trim($lemma)=='') throw new ValueError("пустая строка");

		$result_raw=$this->webClient->send("/qazaq/declension", ['s' => $lemma],'GET',
			[
				'Accept'=> 'application/json',
				'Authorization'=> 'Basic '.$this->webClient->getToken()
			]		  
		);


		$result = json_decode($result_raw,true);

		$result['A']=$lemma;	

		$declensionResult = new DeclensionResult($result);

		return $declensionResult;
	}
	
}