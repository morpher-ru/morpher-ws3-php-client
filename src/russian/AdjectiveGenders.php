<?php

namespace Morpher\Ws3Client\Russian;

class AdjectiveGenders
{
	/**
	 * @readonly
	 */
	public ?string $feminine;
	/**
	 * @readonly
	 */
	public ?string $neuter;
	/**
	 * @readonly
	 */
	public ?string $plural;

	/**
	 * @readonly
	 */
	public array $data;

	public function __construct($data)
	{
		$this->data = $data;

		$this->feminine = $data['feminine'] ?? null;
		$this->neuter = $data['neuter'] ?? null;
		$this->plural = $data['plural'] ?? null;
	}
}
