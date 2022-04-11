<?php
namespace Morpher\Ws3Client\Russian;

require_once __DIR__."/../../vendor/autoload.php";



class Gender
{
    const Masculine='Masculine';
    const Feminine='Feminine';
    const Neuter='Neuter';
    const Plural='Plural';

    const decode_array=['Мужской'=>self::Masculine,'Женский'=>self::Feminine, 'Средний'=>self::Neuter, 'Множественное'=>self::Plural];   

    public static function DecodeName(string $gender_name): string
    {

        return self::decode_array[$gender_name];

    }
}