<?php
namespace Morpher\Ws3Client;


use InvalidArgumentException;
use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Russian\DeclensionResult;
use TypeError;

abstract class UserDictBase
{
    protected WebClient $webClient;

    protected string $endpoint;

    protected string $CorrectionEntryClassName;
    
    function __construct(WebClient $webClient, string $endpoint, string $CorrectionEntryClassName)
    {
        $this->webClient=$webClient;
        $this->endpoint=$endpoint;
        $this->CorrectionEntryClassName=$CorrectionEntryClassName;
    }
    
    protected function AddOrUpdateBase(CorrectionEntryInterface $entry): void
    {
        if (!($entry instanceof $this->CorrectionEntryClassName))
        {
            throw new InvalidArgumentException("$entry не является экземпляром подходящего класса.");
        }

         if (!$entry->SingularNominativeExists())
        {
            throw new \InvalidArgumentException("Обязательно должен быть указан именительный падеж единственного числа.");
        }

        $formParam=$entry->getArrayForRequest();

        if (count($formParam)<2)
        {
            throw new \InvalidArgumentException("Нужно указать хотя бы одну косвенную форму.");
        }

        try
        {
            $this->webClient->send($this->endpoint,[],'POST',null,null,$formParam);
        }
        catch (\Morpher\Ws3Client\MorpherError $ex)
        {
            // todo: проверить ошибку 6

            throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
        }
    }

    public function Remove(string $NominativeForm): void
    {
        if (empty(trim($NominativeForm)))
        {
            throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
        }

        $queryParam=["s"=>$NominativeForm];

        try
        {
            $this->webClient->send($this->endpoint,$queryParam,'DELETE');
        }
        catch (\Morpher\Ws3Client\MorpherError $ex)
        {
            throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
        }
    }

    public function GetAll(): array
    {
        $result_raw="";
        try
        {
            $result_raw=$this->webClient->send($this->endpoint,[],'GET');
        }
        catch (\Morpher\Ws3Client\MorpherError $ex)
        {
            throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
        }

        $result=WebClient::JsonDecode($result_raw);

        $array=array_map(function (array $item) { return new ($this->CorrectionEntryClassName)($item);}, $result );

        return $array;
    }
}