<?php

namespace Morpher\Ws3Client\Ukrainian;

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
}
