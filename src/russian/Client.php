<?php
namespace Morpher\Ws3Client\Russian;

require_once __DIR__."/../../vendor/autoload.php";

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Russian\DeclensionResult;


class Client
{
	private readonly WebClient $webClient;
	public readonly UserDict $userDict;
	
	public function __construct(WebClient $webClient)
	{
		$this->webClient=$webClient;
		$this->userDict=new UserDict($webClient);
	}
	
	public function Parse(string $lemma,array $flags=[]): DeclensionResult
	{
		if (trim($lemma)=='') throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();

		$query=["s"=>$lemma];
		if (!empty($flags))
		{
			$query['flags']=implode(',',$flags);
		}

		$result_raw="";
		try{

			$result_raw=$this->webClient->send("/russian/declension",$query,'GET');
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
		//
		//parse result

		$result['И']=$lemma;
		$declensionResult = new DeclensionResult($result);


		return $declensionResult;
	}
	

	public function Spell(int $number, string $unit): NumberSpellingResult
	{
		if (empty(trim($unit)))
		{
			throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
		}

		$queryParam=["n"=>$number,'unit'=>$unit];

		$result_raw="";
		try{

			$result_raw=$this->webClient->send("/russian/spell",$queryParam,'GET');
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{
			//$morpher_code=$ex->getCode();
			//$msg=$ex->getMessage();

			throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
		}

		$result=WebClient::JsonDecode($result_raw);
		$spellResult = new NumberSpellingResult($result);

		return $spellResult;


	}

	public function SpellOrdinal(int $number, string $unit): NumberSpellingResult
	{
		if (empty(trim($unit)))
		{
			throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
		}

		$queryParam=["n"=>$number,'unit'=>$unit];

		$result_raw="";
		try{

			$result_raw=$this->webClient->send("/russian/spell-ordinal",$queryParam,'GET');
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{
			//$morpher_code=$ex->getCode();
			//$msg=$ex->getMessage();

			throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
		}

		$result=WebClient::JsonDecode($result_raw);
		$spellResult = new NumberSpellingResult($result);

		return $spellResult;


	}


	//yyyy-MM-dd
	public function SpellDate( $date): DateSpellingResult  //$date - string, timestamp, DateTimeInterface
	{

		if (is_int($date))
		{
			$date=date('Y-m-d',$date);
		} else 
		if ($date instanceof \DateTimeInterface)
		{
			$date=$date->format('Y-m-d');
		}

		if (!is_string($date))
		{
			throw new \InvalidArgumentException('Неверный тип: нужна строка, int timestamp или DateTimeInterface.');
		}
		if (empty(trim($date)))
		{
			throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
		}

		$queryParam=["date"=>$date];

		$result_raw="";
		try{

			$result_raw=$this->webClient->send("/russian/spell-date",$queryParam,'GET');
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{
			$morpher_code=$ex->getCode();
			$msg=$ex->getMessage();
			if ($morpher_code==8) throw new IncorrectDateFormat($msg);

			throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
		}

		$result=WebClient::JsonDecode($result_raw);
		$spellResult = new DateSpellingResult($result);

		return $spellResult;


	}


	
	public function AdjectiveGenders(string $adj): AdjectiveGenders
	{
		if (trim($adj)=='') throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
		
		//$query="s=".rawurlencode($adj);
		$query=['s'=>$adj];

		$result_raw="";
		try{

			$result_raw=$this->webClient->send("/russian/genders",$query,'GET');
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{
	
			throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
		}

		$result=WebClient::JsonDecode($result_raw);
		//
		//parse result


		
		$genders = new AdjectiveGenders($result);
		if ($genders->Feminine=='ERROR') throw new AdjectiveFormIncorrect();


		return $genders;
	}

	
	public function Adjectivize(string $name): array
	{
		if (trim($name)=='') throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
		
		$query="s=".urlencode($name);

		$result_raw="";
		try{

			$result_raw=$this->webClient->send("/russian/adjectivize",$query,'GET');
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{
	
			throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
		}

		$result=WebClient::JsonDecode($result_raw);
		//
		//parse result

		return $result;
	}

	public function AddStressmarks(string $text)
	{
		if (trim($text)=='') throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
		
		$result_raw="";
		$headers=$this->webClient->getStandartHeaders();
		$headers['Content-Type']= 'text/plain; charset=utf-8';
		try{

			$result_raw=$this->webClient->send("/russian/addstressmarks",[],'POST',$headers,$text);
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{
	
			throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
		}


		$result=WebClient::JsonDecode($result_raw);
		//
		//parse result

		return $result;
	}

	
}