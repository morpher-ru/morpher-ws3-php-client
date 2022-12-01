<?php
namespace Morpher\Ws3Client\Ukrainian;


class CorrectionEntry implements \Morpher\Ws3Client\CorrectionEntryInterface
{
    public ?CorrectionForms $Singular = null;
    //public ?CorrectionForms $Plural = null;
    //public ?string $Gender;

    function __construct(array $data = [])
    {
        $this->Singular = new CorrectionForms($data['singular'] ?? null);
        //$this->Plural = new CorrectionForms($data['plural'] ?? null);
    }

    /*
    *  returned array is compatible with __construct(array $data = [])
    */
    public function getArray()
    {
        $data = [];
        if (!($this->Singular === null))
            $data['singular'] = $this->Singular->getArray();
        // if (!($this->Plural === null))
        //     $data['plural'] = $this->Plural->getArray();
        return $data;
    }

    /*
    *  returned array is not compatible with __construct(array $data = []).  
    *  returned array is compatible with server request format.
    */
    public function getArrayForRequest():array
    {
        $data = [];
        if (!($this->Singular === null))
            $data = $this->Singular->getArray();
        // if (!($this->Plural === null))
        // {
        //     $data_plural = $this->Plural->getArray();
        //     foreach ($data_plural as $key => $val)
        //     {
        //         if (!($val === null))
        //         {
        //             $data['М_'.$key] = $val;
        //         }
        //     }

        // }

        return $data;
    }

    public function SingularNominativeExists():bool
    {
        if ($this->Singular === null) return false;
        if ($this->Singular->Nominative === null) return false;
        return $this->Singular->Nominative != ''
            && !ctype_space($this->Singular->Nominative);
    }
}