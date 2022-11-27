<?php
namespace Morpher\Ws3Client\Russian;


class NumberSpellingResult
{
    //[DataMember(Name = "n")]
    /**
     * @readonly
     */
    public ?DeclensionForms $NumberDeclension;

    //[DataMember(Name = "unit")]
    /**
     * @readonly
     */
    public ?DeclensionForms $UnitDeclension;
    function __construct($data)
    {
        $this->NumberDeclension=new DeclensionForms($data['n']);
        $this->UnitDeclension=new DeclensionForms($data['unit']);
    }
}