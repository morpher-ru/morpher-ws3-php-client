<?php
namespace Morpher\Ws3Client\Qazaq;


use Morpher\Ws3Client\WebClient;

class Client
{
	/**
  * @readonly
  */
 private WebClient $webClient;
	
	public function __construct(WebClient $webClient)
	{
		$this->webClient=$webClient;
	}
	
	public function Parse(string $lemma): DeclensionResult
	{
		if (trim($lemma)=='') throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();

		$query=["s"=>$lemma];

		try
		{
			$result_raw=$this->webClient->send("/qazaq/declension", $query);
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{
			if ($ex->getCode()==5) throw new QazaqWordsNotFound($ex->getMessage());

			throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
		}

		$result=WebClient::JsonDecode($result_raw);

		$result['A']=$lemma;	

		$declensionResult = new DeclensionResult($result);

		return $declensionResult;
	}
	
}