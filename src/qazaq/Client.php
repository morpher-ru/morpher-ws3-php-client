<?php
namespace Morpher\Ws3Client\Qazaq;

require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/../WebClientBase.php";
require_once "DeclensionResult.php";

use Morpher\Ws3Client\WebClientBase;
use Morpher\Ws3Client\Qazaq\DeclensionResult;
class Client
{
	private $webClient;
	
	public function __construct(WebClientBase $webClient)
	{
		$this->webClient=$webClient;
	}
	
	public function Parse(string $lemma)
	{
		if (trim($lemma)=='') throw new ValueError("пустая строка");
		$result_raw = $this->webClient->get_request("/qazaq/declension", ['s' => $lemma]);
		$result = json_decode($result_raw,true);

		$result['A']=$lemma;	

		$declensionResult = new DeclensionResult($result);

		return $declensionResult;
	}
	
}