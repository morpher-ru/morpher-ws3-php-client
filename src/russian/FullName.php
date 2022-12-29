<?php
namespace Morpher\Ws3Client\Russian;


class FullName
{
    public ?string $Surname;
    public ?string $Name;
    public ?string $Patronymic;

    function __construct(array $data)
    {
        $this->Surname = $data['Ф'] ?? null;
        $this->Name = $data['И'] ?? null;
        $this->Patronymic = $data['О'] ?? null;
    }
}
