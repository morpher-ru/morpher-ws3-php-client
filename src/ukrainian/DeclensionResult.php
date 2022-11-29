<?php
namespace Morpher\Ws3Client\Ukrainian;




class DeclensionResult extends DeclensionForms
{



    public ?string $Gender;


    function __construct($data)
    {
        parent::__construct($data);

   
        $this->Gender=isset($data["рід"]) ? Gender::DecodeName($data["рід"]) : null;



    }   




}