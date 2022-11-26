<?php

namespace Morpher\Ws3Client\Qazaq;

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
	public ?string $ablative;
	/**
	 * @readonly
	 */
	public ?string $locative;
	/**
	 * @readonly
	 */
	public ?string $instrumental;

	public function __construct($data)
	{
		$this->nominative = $data['A'] ?? null;
		$this->genitive = $data['І'] ?? null;
		$this->dative = $data['Б'] ?? null;
		$this->accusative = $data['Т'] ?? null;
		$this->ablative = $data['Ш'] ?? null;
		$this->locative = $data['Ж'] ?? null;
		$this->instrumental = $data['К'] ?? null;
	}
}
