<?php
namespace Morpher\Ws3Client\Russian;


class DeclensionForms
{
    public readonly ?string $Nominative;
    public readonly ?string $Genitive;
    public readonly ?string $Dative;
    public readonly ?string $Accusative;
    public readonly ?string $Instrumental;
    public readonly ?string $Prepositional;
    public readonly ?string $PrepositionalWithO;    

    function __construct($data)
    {
        $this->Nominative=$data['И'] ?? null;         
        $this->Genitive  =$data['Р'] ?? null;     
        $this->Dative    =$data['Д'] ?? null;
        $this->Accusative=$data['В'] ?? null; 
        $this->Instrumental=$data['Т'] ?? null; 
        $this->Prepositional=$data['П'] ?? null; 
        $this->PrepositionalWithO=$data['П_о'] ?? null; 
    }
}