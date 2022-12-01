<?php
namespace Morpher\Ws3Client\Russian;

class AdjectiveFormIncorrect extends \InvalidArgumentException
{
    function __construct(string $message = 'Нарушены требования к входному прилагательному.',int $code = 0)
    {
        parent::__construct($message,$code);
    }
}