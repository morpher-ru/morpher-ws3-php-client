<?php
namespace Morpher\Ws3Client\Qazaq;


class SameNumberForms extends DeclensionForms
{

    //[DataMember(Name = "менің")]
    /**
     * @readonly
     */
    public ?DeclensionForms $FirstPerson;

    //[DataMember(Name = "сенің")]
    /**
     * @readonly
     */
    public ?DeclensionForms $SecondPerson;

    //[DataMember(Name = "сіздің")]
    /**
     * @readonly
     */
    public ?DeclensionForms $SecondPersonRespectful;

    //[DataMember(Name = "оның")]
    /**
     * @readonly
     */
    public ?DeclensionForms $ThirdPerson;

    //[DataMember(Name = "біздің")]
    /**
     * @readonly
     */
    public ?DeclensionForms $FirstPersonPlural;

    //[DataMember(Name = "сендердің")]
    /**
     * @readonly
     */
    public ?DeclensionForms $SecondPersonPlural;

    //[DataMember(Name = "сіздердің")]
    /**
     * @readonly
     */
    public ?DeclensionForms $SecondPersonRespectfulPlural;

    //[DataMember(Name = "олардың")]
    /**
     * @readonly
     */
    public ?DeclensionForms $ThirdPersonPlural;

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