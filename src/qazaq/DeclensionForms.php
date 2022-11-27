<?php
namespace Morpher\Ws3Client\Qazaq;



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
    public ?string $Ablative;
    /**
     * @readonly
     */
    public ?string $Locative;
    /**
     * @readonly
     */
    public ?string $Instrumental;    

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