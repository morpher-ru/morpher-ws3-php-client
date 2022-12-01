<?php
namespace Morpher\Ws3Client\Qazaq;


class SameNumberForms extends DeclensionForms
{
    public ?DeclensionForms $FirstPerson;
    public ?DeclensionForms $SecondPerson;
    public ?DeclensionForms $SecondPersonRespectful;
    public ?DeclensionForms $ThirdPerson;
    public ?DeclensionForms $FirstPersonPlural;
    public ?DeclensionForms $SecondPersonPlural;
    public ?DeclensionForms $SecondPersonRespectfulPlural;
    public ?DeclensionForms $ThirdPersonPlural;

    function __construct($data)
    {
        parent::__construct($data);
 
        $this->FirstPerson = isset($data["менің"]) ? new DeclensionForms($data["менің"]) : null;
        $this->SecondPerson = isset($data["сенің"]) ? new DeclensionForms($data["сенің"]) : null;   
        $this->SecondPersonRespectful = isset($data["сіздің"]) ? new DeclensionForms($data["сіздің"]) : null;                
        $this->ThirdPerson = isset($data["оның"]) ? new DeclensionForms($data["оның"]) : null;   
        $this->FirstPersonPlural = isset($data["біздің"]) ? new DeclensionForms($data["біздің"]) : null;     
        $this->SecondPersonPlural = isset($data["сендердің"]) ? new DeclensionForms($data["сендердің"]) : null;          
        $this->SecondPersonRespectfulPlural = isset($data["сіздердің"]) ? new DeclensionForms($data["сіздердің"]) : null;                      
        $this->ThirdPersonPlural = isset($data["олардың"]) ? new DeclensionForms($data["олардың"]) : null;       
    }
}