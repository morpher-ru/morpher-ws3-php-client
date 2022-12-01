<?php
namespace Morpher\Ws3Client\Russian;


class NumberSpellingResult
{
    public ?DeclensionForms $NumberDeclension;
    public ?DeclensionForms $UnitDeclension;
    
    function __construct($data)
    {
        $this->NumberDeclension = new DeclensionForms($data['n']);
        $this->UnitDeclension = new DeclensionForms($data['unit']);
    }
}