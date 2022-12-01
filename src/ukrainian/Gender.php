<?php

namespace Morpher\Ws3Client\Ukrainian;

class Gender
{
    const Masculine = 'Masculine';
    const Feminine = 'Feminine';

    const decode_array = ['Чоловічий' => self::Masculine,'Жіночий' => self::Feminine];

    public static function DecodeName(string $gender_name): ?string
    {
        return self::decode_array[$gender_name] ?? null;
    }
}