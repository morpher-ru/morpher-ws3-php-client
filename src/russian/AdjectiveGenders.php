<?php
namespace Morpher\Ws3Client\Russian;

class AdjectiveGenders
{
    public ?string $Feminine;

    public ?string $Neuter;

    public ?string $Plural;

    public array $data; 
    function __construct($data)
    {
        $this->data=$data;

        $this->Feminine=$data['feminine'] ?? null;         
        $this->Neuter =$data['neuter'] ?? null;     
        $this->Plural =$data['plural'] ?? null;



    }    
}