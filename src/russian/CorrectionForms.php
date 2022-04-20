<?php
namespace Morpher\Ws3Client\Russian;



class CorrectionForms
{
    public  ?string $Nominative;
    public  ?string $Genitive;
    public  ?string $Dative;
    public  ?string $Accusative;
    public  ?string $Instrumental;
    public  ?string $Prepositional;
    public  ?string $Locative;    

    //public readonly array $data; 
    function __construct($data)
    {
        $this->data=$data;

        $this->Nominative=$data['И'] ?? null;         
        $this->Genitive  =$data['Р'] ?? null;     
        $this->Dative    =$data['Д'] ?? null;
        $this->Accusative=$data['В'] ?? null; 
        $this->Instrumental=$data['Т'] ?? null; 
        $this->Prepositional=$data['П'] ?? null; 
        $this->Locative=$data['М'] ?? null; 


    }

    /*
    *  returned array is compatible with __construct($data)
    */
    public function getArray():array
    {        
        $data=[];
        $data['И']=$this->Nominative;         
        $data['Р']=$this->Genitive;     
        $data['Д']=$this->Dative;
        $data['В']=$this->Accusative; 
        $data['Т']=$this->Instrumental; 
        $data['П']=$this->Prepositional; 
        $data['М']=$this->Locative;        

        $data=array_filter($data,function($var){ return !($var===null); } );
        return $data;


    }

}