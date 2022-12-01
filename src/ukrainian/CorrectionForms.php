<?php
namespace Morpher\Ws3Client\Ukrainian;


class CorrectionForms
{
    public  ?string $Nominative;
    public  ?string $Genitive;
    public  ?string $Dative;
    public  ?string $Accusative;
    public  ?string $Instrumental;
    public  ?string $Prepositional;
    public  ?string $Vocative;    

    function __construct($data)
    {
        $this->Nominative = $data['Н'] ?? null;         
        $this->Genitive  = $data['Р'] ?? null;     
        $this->Dative    = $data['Д'] ?? null;
        $this->Accusative = $data['З'] ?? null; 
        $this->Instrumental = $data['О'] ?? null; 
        $this->Prepositional = $data['М'] ?? null; 
        $this->Vocative = $data['К'] ?? null; 
    }

    /*
    *  returned array is compatible with __construct($data)
    */
    public function getArray():array
    {        
        $data = [];
        $data['Н'] = $this->Nominative;         
        $data['Р'] = $this->Genitive;     
        $data['Д'] = $this->Dative;
        $data['З'] = $this->Accusative; 
        $data['О'] = $this->Instrumental; 
        $data['М'] = $this->Prepositional; 
        $data['К'] = $this->Vocative;        

        $data = array_filter($data,function($var){ return !($var === null); } );
        return $data;
    }
}
