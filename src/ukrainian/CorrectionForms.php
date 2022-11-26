<?php

namespace Morpher\Ws3Client\Ukrainian;

class CorrectionForms
{
	public ?string $nominative;
	public ?string $genitive;
	public ?string $dative;
	public ?string $accusative;
	public ?string $instrumental;
	public ?string $prepositional;
	public ?string $vocative;

	public function __construct($data)
	{
		$this->nominative = $data['Н'] ?? null;
		$this->genitive = $data['Р'] ?? null;
		$this->dative = $data['Д'] ?? null;
		$this->accusative = $data['З'] ?? null;
		$this->instrumental = $data['О'] ?? null;
		$this->prepositional = $data['М'] ?? null;
		$this->vocative = $data['К'] ?? null;
	}

	/*
	*  returned array is compatible with __construct($data)
	*/
	public function getArray(): array
	{
		$data = [];
		$data['Н'] = $this->nominative;
		$data['Р'] = $this->genitive;
		$data['Д'] = $this->dative;
		$data['З'] = $this->accusative;
		$data['О'] = $this->instrumental;
		$data['М'] = $this->prepositional;
		$data['К'] = $this->vocative;

		return array_filter($data, static fn ($var) => !($var === null));
	}
}
