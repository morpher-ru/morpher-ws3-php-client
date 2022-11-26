<?php

namespace Morpher\Ws3Client\Russian;

class FullName
{
	/**
	 * @readonly
	 */
	public ?string $surname;
	/**
	 * @readonly
	 */
	public ?string $name;
	/**
	 * @readonly
	 */
	public ?string $patronymic;

	public function __construct(array $data)
	{
		$this->surname = $data['Ф'] ?? null;
		$this->name = $data['И'] ?? null;
		$this->patronymic = $data['О'] ?? null;
	}
}
