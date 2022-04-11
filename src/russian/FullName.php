<?php
namespace Morpher\Ws3Client\Russian;

require_once __DIR__."/../../vendor/autoload.php";


class FullName
{
    
    public readonly ?string $Surname;
    public readonly ?string $Name;
    public readonly ?string $Pantronymic;

    function __construct(array $data)
    {
        $this->Surname=$data['Ф'] ?? null;
        $this->Name=$data['И'] ?? null;
        $this->Pantronymic=$data['О'] ?? null;
    }

}