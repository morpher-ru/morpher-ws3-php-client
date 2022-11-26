<?php

namespace Morpher\Ws3Client\Russian;

class DeclensionForms
{
	/**
	 * @readonly
	 */
	public ?string $nominative;
	/**
	 * @readonly
	 */
	public ?string $genitive;
	/**
	 * @readonly
	 */
	public ?string $dative;
	/**
	 * @readonly
	 */
	public ?string $accusative;
	/**
	 * @readonly
	 */
	public ?string $instrumental;
	/**
	 * @readonly
	 */
	public ?string $prepositional;
	/**
	 * @readonly
	 */
	public ?string $prepositionalWithO;

	/**
	 * @readonly
	 */
	public array $data;

	public function __construct($data)
	{
		$this->data = $data;

		$this->nominative = $data['И'] ?? null;
		$this->genitive = $data['Р'] ?? null;
		$this->dative = $data['Д'] ?? null;
		$this->accusative = $data['В'] ?? null;
		$this->instrumental = $data['Т'] ?? null;
		$this->prepositional = $data['П'] ?? null;
		$this->prepositionalWithO = $data['П_о'] ?? null;
	}
}
