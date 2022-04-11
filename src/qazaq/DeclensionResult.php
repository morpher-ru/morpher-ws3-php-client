<?php
namespace Morpher\Ws3Client\Qazaq;

require_once __DIR__."/../../vendor/autoload.php";

require_once "SameNumberForms.php";

class DeclensionResult extends SameNumberForms
{

    public readonly SameNumberForms $Plural;

    function __construct($data,$data_plural)
    {
        parent::__construct($data);
        $this->Plural=new SameNumberForms($data_plural);

    }



}