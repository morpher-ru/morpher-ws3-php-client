<?php
namespace Morpher\Ws3Client\Qazaq;

require_once __DIR__."/../../vendor/autoload.php";


class DeclensionForms
{
    public readonly ?string $Nominative;
    public readonly ?string $Genitive;
    public readonly ?string $Dative;
    public readonly ?string $Accusative;
    public readonly ?string $Ablative;
    public readonly ?string $Locative;
    public readonly ?string $Instrumental;    

    //public readonly array $data; 
    function __construct($data)
    {
        //$this->data=$data;

        $this->Nominative=$data['A'] ?? null;
        $this->Genitive  =$data['І'] ?? null;        
        $this->Dative    =$data['Б'] ?? null;
        $this->Accusative=$data['Т'] ?? null; 
        $this->Ablative=$data['Ш'] ?? null; 
        $this->Locative=$data['Ж'] ?? null; 
        $this->Instrumental=$data['К'] ?? null; 


    }



}