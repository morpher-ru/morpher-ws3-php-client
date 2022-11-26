<?php

namespace Morpher\Ws3Client\Ukrainian;

class DeclensionResult extends DeclensionForms
{
	/**
	 * @readonly
	 */
	public ?string $gender;

	public function __construct($data)
	{
		parent::__construct($data);

		$this->gender = isset($data["рід"]) ? Gender::decodeName($data["рід"]) : null;
	}
}
