<?php
namespace Morpher\Ws3Client\Russian;

class AdjectiveGenders
{
    /**
     * @readonly
     */
    public ?string $Feminine;
    /**
     * @readonly
     */
    public ?string $Neuter;
    /**
     * @readonly
     */
    public ?string $Plural;

    /**
     * @readonly
     */
    public array $data; 
    function __construct($data)
    {
        $this->data=$data;

        $this->Feminine=$data['feminine'] ?? null;         
        $this->Neuter =$data['neuter'] ?? null;     
        $this->Plural =$data['plural'] ?? null;



    }    
}