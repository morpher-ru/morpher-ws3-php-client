<?php

namespace Morpher\Ws3Client\Qazaq;

class DeclensionResult extends SameNumberForms
{
	/**
	 * @readonly
	 */
	public ?SameNumberForms $plural;

	public function __construct($data)
	{
		parent::__construct($data);

		$this->plural = isset($data['көпше']) ? new SameNumberForms($data['көпше']) : null;
	}
}
