<?php

namespace Morpher\Ws3Client\Qazaq;

class SameNumberForms extends DeclensionForms
{
	//[DataMember(Name = "менің")]
	/**
	 * @readonly
	 */
	public ?DeclensionForms $firstPerson;

	//[DataMember(Name = "сенің")]
	/**
	 * @readonly
	 */
	public ?DeclensionForms $secondPerson;

	//[DataMember(Name = "сіздің")]
	/**
	 * @readonly
	 */
	public ?DeclensionForms $secondPersonRespectful;

	//[DataMember(Name = "оның")]
	/**
	 * @readonly
	 */
	public ?DeclensionForms $thirdPerson;

	//[DataMember(Name = "біздің")]
	/**
	 * @readonly
	 */
	public ?DeclensionForms $firstPersonPlural;

	//[DataMember(Name = "сендердің")]
	/**
	 * @readonly
	 */
	public ?DeclensionForms $secondPersonPlural;

	//[DataMember(Name = "сіздердің")]
	/**
	 * @readonly
	 */
	public ?DeclensionForms $secondPersonRespectfulPlural;

	//[DataMember(Name = "олардың")]
	/**
	 * @readonly
	 */
	public ?DeclensionForms $thirdPersonPlural;

	public function __construct($data)
	{
		parent::__construct($data);

		$this->firstPerson = isset($data["менің"]) ? new DeclensionForms($data["менің"]) : null;
		$this->secondPerson = isset($data["сенің"]) ? new DeclensionForms($data["сенің"]) : null;
		$this->secondPersonRespectful = isset($data["сіздің"]) ? new DeclensionForms($data["сіздің"]) : null;
		$this->thirdPerson = isset($data["оның"]) ? new DeclensionForms($data["оның"]) : null;
		$this->firstPersonPlural = isset($data["біздің"]) ? new DeclensionForms($data["біздің"]) : null;
		$this->secondPersonPlural = isset($data["сендердің"]) ? new DeclensionForms($data["сендердің"]) : null;
		$this->secondPersonRespectfulPlural = isset($data["сіздердің"]) ? new DeclensionForms($data["сіздердің"]) : null;
		$this->thirdPersonPlural = isset($data["олардың"]) ? new DeclensionForms($data["олардың"]) : null;
	}
}
