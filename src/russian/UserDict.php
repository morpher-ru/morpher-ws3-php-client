<?php
namespace Morpher\Ws3Client\Russian;


use Morpher\Ws3Client\SystemError;
use Morpher\Ws3Client\TokenRequired;
use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\UserDictBase;


class UserDict extends UserDictBase 
{
    function __construct(WebClient $webClient)
    {
        parent::__construct($webClient,'/russian/userdict', CorrectionEntry::class);
    }

    /**
     * @throws TokenRequired
     * @throws SystemError
     */
    public function AddOrUpdate(CorrectionEntry $entry): void
    {
        $this->addOrUpdateBase($entry);
    }
}
