<?php
namespace Morpher\Ws3Client\Russian;

class AdjectiveGenders
{
    public readonly ?string $Feminine;
    public readonly ?string $Neuter;
    public readonly ?string $Plural;

    public readonly array $data; 
    function __construct($data)
    {
        $this->data=$data;

        $this->Feminine=$data['feminine'] ?? null;         
        $this->Neuter =$data['neuter'] ?? null;     
        $this->Plural =$data['plural'] ?? null;
    }    
}