<?php

namespace Morpher\Ws3Client\Ukrainian;

class DeclensionForms
{
	public ?string $Nominative;

	public ?string $Genitive;

	public ?string $Dative;

	public ?string $Accusative;

	public ?string $Instrumental;

	public ?string $Prepositional;

	public ?string $Vocative;

	public array $data;

	function __construct($data)
	{
		$this->data = $data;

		$this->Nominative = $data['Н'] ?? null;
		$this->Genitive = $data['Р'] ?? null;
		$this->Dative = $data['Д'] ?? null;
		$this->Accusative = $data['З'] ?? null;
		$this->Instrumental = $data['О'] ?? null;
		$this->Prepositional = $data['М'] ?? null;
		$this->Vocative = $data['К'] ?? null;

	}

}