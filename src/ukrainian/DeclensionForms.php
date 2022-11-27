<?php
namespace Morpher\Ws3Client\Ukrainian;



class DeclensionForms
{
    /**
     * @readonly
     */
    public ?string $Nominative;
    /**
     * @readonly
     */
    public ?string $Genitive;
    /**
     * @readonly
     */
    public ?string $Dative;
    /**
     * @readonly
     */
    public ?string $Accusative;
    /**
     * @readonly
     */
    public ?string $Instrumental;
    /**
     * @readonly
     */
    public ?string $Prepositional;
    /**
     * @readonly
     */
    public ?string $Vocative;    

    /**
     * @readonly
     */
    public array $data; 
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