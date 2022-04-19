<?php
namespace Morpher\Ws3Client\Ukrainian;

require_once __DIR__."/../../vendor/autoload.php";



class Gender
{
    const Masculine='Masculine';
    const Feminine='Feminine';
    const Neuter='Neuter';
    const Plural='Plural';

    const decode_array=['Чоловічий'=>self::Masculine,'Жіночий'=>self::Feminine, 'Середній'=>self::Neuter, 'Множина'=>self::Plural];   

    public static function DecodeName(string $gender_name): ?string
    {
        if ($gender_name=='') return self::Plural; //Множественное число кодируется пустым тегом

        return self::decode_array[$gender_name] ?? null;

    }
}