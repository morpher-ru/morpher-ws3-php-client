<?php

namespace Morpher\Ws3Client\Russian;

class CorrectionForms
{
	public ?string $nominative;
	public ?string $genitive;
	public ?string $dative;
	public ?string $accusative;
	public ?string $instrumental;
	public ?string $prepositional;
	public ?string $locative;

	public function __construct($data)
	{
		$this->nominative = $data['И'] ?? null;
		$this->genitive = $data['Р'] ?? null;
		$this->dative = $data['Д'] ?? null;
		$this->accusative = $data['В'] ?? null;
		$this->instrumental = $data['Т'] ?? null;
		$this->prepositional = $data['П'] ?? null;
		$this->locative = $data['М'] ?? null;
	}

	/*
	*  returned array is compatible with __construct($data)
	*/
	public function getArray(): array
	{
		$data = [];

		$data['И'] = $this->nominative;
		$data['Р'] = $this->genitive;
		$data['Д'] = $this->dative;
		$data['В'] = $this->accusative;
		$data['Т'] = $this->instrumental;
		$data['П'] = $this->prepositional;
		$data['М'] = $this->locative;

		return array_filter($data, static fn ($var) => !($var === null));
	}
}
