<?php
namespace Morpher\Ws3Client\Russian;




class DeclensionResult extends DeclensionForms
{


    //protected $declensionForms_plural=null;
    /**
     * @readonly
     */
    public ?DeclensionForms $Plural;

    /**
     * @readonly
     */
    public ?string $Gender;
    /**
     * @readonly
     */
    public ?string $Where;
    /**
     * @readonly
     */
    public ?string $From;
    /**
     * @readonly
     */
    public ?string $To;
    /**
     * @readonly
     */
    public ?FullName $FullName;

    function __construct($data)
    {
        parent::__construct($data);

        $this->Plural=isset($data['множественное']) ? new DeclensionForms($data['множественное']) : null;
  
        $this->Gender=isset($data["род"]) ? Gender::DecodeName($data["род"]) : null;

        $this->FullName=isset($data['ФИО']) ?  new FullName($data['ФИО']) : null;

        $this->Where=$data["где"] ?? null;

        $this->To=$data["куда"] ?? null;        
        
        $this->From=$data["откуда"] ?? null;

    }   




}