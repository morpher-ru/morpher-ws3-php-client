<?php
namespace Morpher\Ws3Client\Ukrainian;

require_once __DIR__."/../../vendor/autoload.php";



class DeclensionResult extends DeclensionForms
{



    public readonly ?string $Gender;


    function __construct($data)
    {
        parent::__construct($data);

   
        $this->Gender=isset($data["рід"]) ? Gender::DecodeName($data["рід"]) : null;



    }   




}