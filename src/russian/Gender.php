<?php

namespace Morpher\Ws3Client\Russian;

class Gender
{
	public const MASCULINE = 'Masculine';
	public const FEMININE = 'Feminine';
	public const NEUTER = 'Neuter';
	public const PLURAL = 'Plural';

	public const decode_array = [
		'Мужской' => self::MASCULINE,
		'Женский' => self::FEMININE,
		'Средний' => self::NEUTER,
		'Множественное' => self::PLURAL
	];

	public static function decodeName(string $gender_name): ?string
	{
		if ($gender_name == '')
		{
			return self::PLURAL;
		} //Множественное число кодируется пустым тегом

		return self::decode_array[$gender_name] ?? null;
	}
}
