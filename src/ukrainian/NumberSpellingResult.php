<?php
namespace Morpher\Ws3Client\Ukrainian;

require_once __DIR__."/../../vendor/autoload.php";

class NumberSpellingResult
{
    //[DataMember(Name = "n")]
    public readonly ?DeclensionForms $NumberDeclension;

    //[DataMember(Name = "unit")]
    public readonly ?DeclensionForms $UnitDeclension;
    function __construct($data)
    {
        $this->NumberDeclension=new DeclensionForms($data['n']);
        $this->UnitDeclension=new DeclensionForms($data['unit']);
    }
}