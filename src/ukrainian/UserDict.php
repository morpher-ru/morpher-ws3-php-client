<?php
namespace Morpher\Ws3Client\Ukrainian;


use Morpher\Ws3Client\SystemError;
use Morpher\Ws3Client\TokenRequired;
use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\UserDictBase;


class UserDict extends UserDictBase 
{
    function __construct(WebClient $webClient)
    {
        parent::__construct($webClient,'/ukrainian/userdict', CorrectionEntry::class);
    }

    /**
     * @throws TokenRequired
     * @throws SystemError
     * @throws \InvalidArgumentException
     */
    public function addOrUpdate(CorrectionEntry $entry): void
    {
        $this->addOrUpdateBase($entry);
    }
}
