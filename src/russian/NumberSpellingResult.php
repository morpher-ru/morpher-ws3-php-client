<?php

namespace Morpher\Ws3Client\Russian;

class NumberSpellingResult
{
	//[DataMember(Name = "n")]
	public ?DeclensionForms $NumberDeclension;

	//[DataMember(Name = "unit")]
	public ?DeclensionForms $UnitDeclension;

	function __construct($data)
	{
		$this->NumberDeclension = new DeclensionForms($data['n']);
		$this->UnitDeclension = new DeclensionForms($data['unit']);
	}
}