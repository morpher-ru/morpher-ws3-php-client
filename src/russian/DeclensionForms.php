<?php
namespace Morpher\Ws3Client\Russian;



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
    public ?string $PrepositionalWithO;    

    /**
     * @readonly
     */
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