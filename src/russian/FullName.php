<?php
namespace Morpher\Ws3Client\Russian;



class FullName
{
    
    /**
     * @readonly
     */
    public ?string $Surname;
    /**
     * @readonly
     */
    public ?string $Name;
    /**
     * @readonly
     */
    public ?string $Pantronymic;

    function __construct(array $data)
    {
        $this->Surname=$data['Ф'] ?? null;
        $this->Name=$data['И'] ?? null;
        $this->Pantronymic=$data['О'] ?? null;
    }

}