<?php
namespace Morpher\Ws3Client\Qazaq;


class DeclensionResult extends SameNumberForms
{
	public ?SameNumberForms $Plural;

    function __construct($data)
    {
        parent::__construct($data);

        $this->Plural= isset($data['көпше']) ? new SameNumberForms($data['көпше']) : null;


    }



}