<?php
namespace Morpher\Ws3Client\Qazaq;

require_once __DIR__."/../../vendor/autoload.php";


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
		if (trim($lemma)=='') throw new \InvalidArgumentException("пустая строка");

		try
		{
			$result_raw=$this->webClient->send("/qazaq/declension", ['s' => $lemma],'GET',
				[
					'Accept'=> 'application/json',
					'Authorization'=> 'Basic '.$this->webClient->getToken()
				]		  
			);
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{
			if ($ex->getCode()==5) throw new QazaqWordsNotFound($ex->getMessage());

			throw $ex;
		}

		try
		{
			$result = json_decode($result_raw,true,512,JSON_THROW_ON_ERROR);
		}
		catch (\JsonException $ex)
		{
			throw new \Morpher\Ws3Client\InvalidServerResponse("Некорректный JSON ответ от сервера",$result_raw);
		}
		//

		$result['A']=$lemma;	

		$declensionResult = new DeclensionResult($result);

		return $declensionResult;
	}
	
}