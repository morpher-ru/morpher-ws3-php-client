<?php

namespace Morpher\Ws3Client\Russian;

class DeclensionResult extends DeclensionForms
{
	//protected $declensionForms_plural=null;
	/**
	 * @readonly
	 */
	public ?DeclensionForms $plural;

	/**
	 * @readonly
	 */
	public ?string $gender;
	/**
	 * @readonly
	 */
	public ?string $where;
	/**
	 * @readonly
	 */
	public ?string $from;
	/**
	 * @readonly
	 */
	public ?string $to;
	/**
	 * @readonly
	 */
	public ?FullName $fullName;

	public function __construct($data)
	{
		parent::__construct($data);

		$this->plural = isset($data['множественное']) ? new DeclensionForms($data['множественное']) : null;

		$this->gender = isset($data["род"]) ? Gender::DecodeName($data["род"]) : null;

		$this->fullName = isset($data['ФИО']) ? new FullName($data['ФИО']) : null;

		$this->where = $data["где"] ?? null;

		$this->to = $data["куда"] ?? null;

		$this->from = $data["откуда"] ?? null;
	}
}
