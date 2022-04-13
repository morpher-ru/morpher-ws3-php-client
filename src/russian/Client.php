<?php
namespace Morpher\Ws3Client\Russian;

require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/../WebClient.php";
require_once "DeclensionResult.php";
require_once __DIR__."/../exceptions/MorpherError.php";
require_once "exceptions/RussianWordsNotFound.php";

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Russian\DeclensionResult;


class Client
{
	private $webClient;
	
	public function __construct(WebClient $webClient)
	{
		$this->webClient=$webClient;
	}
	
	public function Parse(string $lemma,array $flags=[])
	{
		if (trim($lemma)=='') throw new \Morpher\Ws3Client\EmptyString();
		
		$query="s=".rawurlencode($lemma);
		if (!empty($flags))
		{
			$query.="&flags=".implode(',',$flags);
		}
		try{
			$result_raw=$this->webClient->send("/russian/declension",$query,'GET',
				[
					'Accept'=> 'application/json',
					'Authorization'=> 'Basic '.$this->webClient->getToken()
				]		  
			);
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{
			if ($ex->getCode()==5) throw new RussianWordsNotFound($ex->getMessage());

			throw $ex;
		}
		$result = json_decode($result_raw,true);
		//
		//parse result

		$result['И']=$lemma;
		$declensionResult = new DeclensionResult($result);


		return $declensionResult;
	}
	
}