<?php
namespace Morpher\Ws3Client\Russian;

require_once __DIR__."/../../vendor/autoload.php";


class FullName
{
    
    public readonly string $Surname;
    public readonly string $Name;
    public readonly string $Pantronymic;

    // function __construct(string $Surname,string $Name,string $Pantronymic)
    // {
    //     $this->Surname=$Surname;
    //     $this->Name=$Name;
    //     $this->Pantronymic=$Pantronymic;
    // }

    function __construct(array $data)
    {
        $this->Surname=$data['Ф'];
        $this->Name=$data['И'];
        $this->Pantronymic=$data['О'];
    }

}