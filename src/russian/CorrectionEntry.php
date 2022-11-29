<?php

namespace Morpher\Ws3Client\Russian;

use Morpher\Ws3Client\CorrectionEntryInterface;

class CorrectionEntry implements \Morpher\Ws3Client\CorrectionEntryInterface
{
    public ?CorrectionForms $Singular = null;
    public ?CorrectionForms $Plural = null;

    //public ?string $Gender=null;

    function __construct(array $data = [])
    {

        $this->Singular = new CorrectionForms($data['singular'] ?? null);
        $this->Plural = new CorrectionForms($data['plural'] ?? null);
        //$this->Gender=isset($data['gender']) ? Gender::DecodeName($data['gender']) : null;

    }

    /*
    *  returned array is compatible with __construct(array $data=[])
    */
    public function getArray()
    {
        $data = [];
        if (!($this->Singular === null))
        {
            $data['singular'] = $this->Singular->getArray();
        }
        if (!($this->Plural === null))
        {
            $data['plural'] = $this->Plural->getArray();
        }

        /*if (!($this->Gender===null))
            $data['gender']=$this->Gender;*/

        return $data;
    }

    /*
    *  returned array is not compatible with __construct(array $data=[]).
    *  returned array is compatible with server request format.
    */
    public function getArrayForRequest(): array
    {
        $data = [];
        if (!($this->Singular === null))
        {
            $data = $this->Singular->getArray();
        }
        if (!($this->Plural === null))
        {
            $data_plural = $this->Plural->getArray();
            foreach ($data_plural as $key => $val)
            {
                if (!($val === null))
                {
                    $data['М_' . $key] = $val;
                }
            }

        }

        /*if (!($this->Gender===null))
            $data['gender']=$this->Gender;*/

        return $data;

    }

    public function SingularNominativeExists(): bool
    {
        if ($this->Singular === null)
        {
            return false;
        }

        return !empty(trim($this->Singular->Nominative));
    }

}