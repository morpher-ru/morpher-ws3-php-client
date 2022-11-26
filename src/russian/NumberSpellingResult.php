<?php

namespace Morpher\Ws3Client\Russian;

class NumberSpellingResult
{
	/**
	 * @readonly
	 */
	public ?DeclensionForms $numberDeclension;

	/**
	 * @readonly
	 */
	public ?DeclensionForms $unitDeclension;

	public function __construct($data)
	{
		$this->numberDeclension = new DeclensionForms($data['n']);
		$this->unitDeclension = new DeclensionForms($data['unit']);
	}
}
