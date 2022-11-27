<?php
namespace Morpher\Ws3Client\Russian;



class DeclensionForms
{
    public ?string $Nominative;

    public ?string $Genitive;

    public ?string $Dative;

    public ?string $Accusative;

    public ?string $Instrumental;

    public ?string $Prepositional;

    public ?string $PrepositionalWithO;    

    public array $data; 
    function __construct($data)
    {
        $this->data=$data;

        $this->Nominative=$data['И'] ?? null;         
        $this->Genitive  =$data['Р'] ?? null;     
        $this->Dative    =$data['Д'] ?? null;
        $this->Accusative=$data['В'] ?? null; 
        $this->Instrumental=$data['Т'] ?? null; 
        $this->Prepositional=$data['П'] ?? null; 
        $this->PrepositionalWithO=$data['П_о'] ?? null; 


    }

}