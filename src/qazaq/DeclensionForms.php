<?php
namespace Morpher\Ws3Client\Qazaq;

class DeclensionForms
{
    public ?string $Nominative;
    public ?string $Genitive;
    public ?string $Dative;
    public ?string $Accusative;
    public ?string $Ablative;
    public ?string $Locative;
    public ?string $Instrumental;    

    function __construct($data)
    {
        $this->Nominative = $data['A'] ?? null;
        $this->Genitive  = $data['І'] ?? null;        
        $this->Dative    = $data['Б'] ?? null;
        $this->Accusative = $data['Т'] ?? null; 
        $this->Ablative  = $data['Ш'] ?? null; 
        $this->Locative  = $data['Ж'] ?? null; 
        $this->Instrumental = $data['К'] ?? null; 
    }
}