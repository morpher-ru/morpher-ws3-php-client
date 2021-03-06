<?php
namespace Morpher\Ws3Client\Ukrainian;



class DeclensionForms
{
    public readonly ?string $Nominative;
    public readonly ?string $Genitive;
    public readonly ?string $Dative;
    public readonly ?string $Accusative;
    public readonly ?string $Instrumental;
    public readonly ?string $Prepositional;
    public readonly ?string $Vocative;    

    public readonly array $data; 
    function __construct($data)
    {
        $this->data=$data;

        $this->Nominative=$data['Н'] ?? null;         
        $this->Genitive  =$data['Р'] ?? null;     
        $this->Dative    =$data['Д'] ?? null;
        $this->Accusative=$data['З'] ?? null; 
        $this->Instrumental=$data['О'] ?? null; 
        $this->Prepositional=$data['М'] ?? null; 
        $this->Vocative=$data['К'] ?? null; 


    }

}