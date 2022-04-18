<?php
namespace Morpher\Ws3Client\Russian;

require_once __DIR__."/../../vendor/autoload.php";

use Morpher\Ws3Client\WebClient;

class Client
{
	private readonly WebClient $webClient;
	
	public function __construct(WebClient $webClient)
	{
		$this->webClient=$webClient;
	}

	public function Parse(string $lemma,array $flags=[]): DeclensionResult
	{
		if (trim($lemma)=='') throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();

		$query=["s"=>$lemma];
		if (!empty($flags))
		{
			$query['flags']=implode(',',$flags);
		}

		try
        {
			$result_raw=$this->webClient->send("/russian/declension", $query);
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{
			$morpher_code=$ex->getCode();
			$msg=$ex->getMessage();
			if ($morpher_code==5) throw new RussianWordsNotFound($msg);
			if ($morpher_code==12) throw new InvalidFlags($msg);
			if ($morpher_code==4) throw new DeclensionNotSupportedUseSpell($msg);
			
			throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
		}

		$result=WebClient::JsonDecode($result_raw);

		$result['И']=$lemma;

		$declensionResult = new DeclensionResult($result);

		return $declensionResult;
	}
	
}