<?php
namespace Morpher\Ws3Client\Russian;




class DeclensionResult extends DeclensionForms
{


    //protected $declensionForms_plural=null;
    public readonly ?DeclensionForms $Plural;

    public readonly ?string $Gender;
    public readonly ?string $Where;
    public readonly ?string $From;
    public readonly ?string $To;
    public readonly ?FullName $FullName;

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