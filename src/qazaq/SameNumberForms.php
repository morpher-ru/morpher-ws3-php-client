<?php
namespace Morpher\Ws3Client\Qazaq;

require_once __DIR__."/../../vendor/autoload.php";
require_once "DeclensionForms.php";

class SameNumberForms extends DeclensionForms
{

    //[DataMember(Name = "менің")]
    public readonly DeclensionForms $FirstPerson;

    //[DataMember(Name = "сенің")]
    public readonly DeclensionForms $SecondPerson;

    //[DataMember(Name = "сіздің")]
    public readonly DeclensionForms $SecondPersonRespectful;

    //[DataMember(Name = "оның")]
    public readonly DeclensionForms $ThirdPerson;

    //[DataMember(Name = "біздің")]
    public readonly DeclensionForms $FirstPersonPlural;

    //[DataMember(Name = "сендердің")]
    public readonly DeclensionForms $SecondPersonPlural;

    //[DataMember(Name = "сіздердің")]
    public readonly DeclensionForms $SecondPersonRespectfulPlural;

    //[DataMember(Name = "олардың")]
    public readonly DeclensionForms $ThirdPersonPlural;

    function __construct($data)
    {
        parent::__construct($data);



        if (isset($data["менің"]))
            $this->FirstPerson=new DeclensionForms($data["менің"]);

        if (isset($data["сенің"]))           
            $this->SecondPerson=new DeclensionForms($data["сенің"]);

        if (isset($data["сіздің"]))               
            $this->SecondPersonRespectful=new DeclensionForms($data["сіздің"]);
 
        if (isset($data["оның"]))                     
            $this->ThirdPerson=new DeclensionForms($data["оның"]);
         
        if (isset($data["біздің"]))                 
            $this->FirstPersonPlural=new DeclensionForms($data["біздің"]);

        if (isset($data["сендердің"]))                      
            $this->SecondPersonPlural=new DeclensionForms($data["сендердің"]);

        if (isset($data["сіздердің"]))                
            $this->SecondPersonRespectfulPlural=new DeclensionForms($data["сіздердің"]);

        if (isset($data["олардың"]))                            
            $this->ThirdPersonPlural=new DeclensionForms($data["олардың"]);       

    }


}