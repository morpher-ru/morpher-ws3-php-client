<?php
namespace Morpher\Ws3Client\Russian;

class IncorrectDateFormat extends \InvalidArgumentException
{
    function __construct(string $message='Дата указана в некорректном формате.',int $code=0)
    {
        parent::__construct($message,$code);
    }
}