<?php
namespace Morpher\Ws3Client\Qazaq;

require_once __DIR__."/../../vendor/autoload.php";

class SameNumberForms extends DeclensionForms
{

    //[DataMember(Name = "менің")]
    public readonly ?DeclensionForms $FirstPerson;

    //[DataMember(Name = "сенің")]
    public readonly ?DeclensionForms $SecondPerson;

    //[DataMember(Name = "сіздің")]
    public readonly ?DeclensionForms $SecondPersonRespectful;

    //[DataMember(Name = "оның")]
    public readonly ?DeclensionForms $ThirdPerson;

    //[DataMember(Name = "біздің")]
    public readonly ?DeclensionForms $FirstPersonPlural;

    //[DataMember(Name = "сендердің")]
    public readonly ?DeclensionForms $SecondPersonPlural;

    //[DataMember(Name = "сіздердің")]
    public readonly ?DeclensionForms $SecondPersonRespectfulPlural;

    //[DataMember(Name = "олардың")]
    public readonly ?DeclensionForms $ThirdPersonPlural;

    function __construct($data)
    {
        parent::__construct($data);
 
        $this->FirstPerson=isset($data["менің"]) ? new DeclensionForms($data["менің"]) : null;
        $this->SecondPerson=isset($data["сенің"]) ? new DeclensionForms($data["сенің"]) : null;   
        $this->SecondPersonRespectful= isset($data["сіздің"]) ? new DeclensionForms($data["сіздің"]) : null;                
        $this->ThirdPerson=isset($data["оның"]) ? new DeclensionForms($data["оның"]) : null;   
        $this->FirstPersonPlural=isset($data["біздің"]) ? new DeclensionForms($data["біздің"]) : null;     
        $this->SecondPersonPlural=isset($data["сендердің"]) ? new DeclensionForms($data["сендердің"]) : null;          
        $this->SecondPersonRespectfulPlural=isset($data["сіздердің"]) ? new DeclensionForms($data["сіздердің"]) : null;                      
        $this->ThirdPersonPlural=isset($data["олардың"]) ? new DeclensionForms($data["олардың"]) : null;       

    }


}