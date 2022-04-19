<?php
namespace Morpher\Ws3Client;

require_once __DIR__."/../vendor/autoload.php";

use InvalidArgumentException;
use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Russian\DeclensionResult;
use TypeError;

abstract class UserDictBase
{
	protected readonly WebClient $webClient;

    protected readonly string $endpoint;

    protected readonly string $CorrectionEntryClassName;
	
	function __construct(WebClient $webClient,string $endpoint='/russian/userdict', string $CorrectionEntryClassName)
	{
		$this->webClient=$webClient;
        $this->endpoint=$endpoint;
        $this->CorrectionEntryClassName=$CorrectionEntryClassName;
	}
	



	//yyyy-MM-dd
	public function AddOrUpdate(CorrectionEntryInterface $entry): void //$date - string, timestamp, DateTimeInterface
	{
        if (!($entry instanceof $this->CorrectionEntryClassName)) throw new InvalidArgumentException("$entry не является экземпляром подходящего класса.");
 		if (!$entry->SingularNominativeExists())
		{
			throw new \InvalidArgumentException("Обязательно должен быть указан именительный падеж единственного числа.");
		}

		$formParam=$entry->getArrayForRequest();

        if (count($formParam)<2)
        {
            throw new \InvalidArgumentException("Нужно указать хотя бы одну косвенную форму.");
        }

		$result_raw="";
		try{

			$result_raw=$this->webClient->send($this->endpoint,[],'POST',null,null,$formParam);
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{

			throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
		}



	}


	
	public function Remove(string $NominativeForm): void //$date - string, timestamp, DateTimeInterface
	{


		if (empty(trim($NominativeForm)))
		{
			throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
		}

		$queryParam=["s"=>$NominativeForm];

		$result_raw="";
		try{

			$result_raw=$this->webClient->send($this->endpoint,$queryParam,'DELETE');
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{

			throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
		}

	}

	public function GetAll(): array //$date - string, timestamp, DateTimeInterface
	{
		$result_raw="";
		try{

			$result_raw=$this->webClient->send($this->endpoint,[],'GET');
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{

			throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
		}

		$result=WebClient::JsonDecode($result_raw);
        //print_r($result);

        $array=array_map(function (array $item) { return new ($this->CorrectionEntryClassName)($item);}, $result );

        return $array;





	}

	
}