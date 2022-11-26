<?php

namespace Morpher\Ws3Client\Ukrainian;

class Gender
{
	public const MASCULINE = 'Masculine';
	public const FEMININE = 'Feminine';

	public const decode_array = [
		'Чоловічий' => self::MASCULINE,
		'Жіночий' => self::FEMININE
	];

	public static function decodeName(string $gender_name): ?string
	{
		return self::decode_array[$gender_name] ?? null;
	}
}
