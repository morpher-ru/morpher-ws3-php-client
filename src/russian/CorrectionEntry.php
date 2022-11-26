<?php

namespace Morpher\Ws3Client\Russian;

use Morpher\Ws3Client\CorrectionEntryInterface;

class CorrectionEntry implements CorrectionEntryInterface
{
	public ?CorrectionForms $singular = null;
	public ?CorrectionForms $plural = null;

	//public ?string $gender = null;

	public function __construct(array $data = [])
	{
		$this->singular = new CorrectionForms($data['singular'] ?? null);
		$this->plural = new CorrectionForms($data['plural'] ?? null);
		//$this->gender = isset($data['gender']) ? Gender::decodeName($data['gender']) : null;
	}

	/*
	*  returned array is compatible with __construct(array $data=[])
	*/
	public function getArrayForRequest(): array
	{
		$data = [];
		if (!($this->singular === null))
		{
			$data = $this->singular->getArray();
		}
		if (!($this->plural === null))
		{
			$data_plural = $this->plural->getArray();
			foreach ($data_plural as $key => $val)
			{
				if (!($val === null))
				{
					$data['М_' . $key] = $val;
				}
			}
		}

		/*if (!($this->gender === null))
			$data['gender'] = $this->gender;*/

		return $data;
	}

	/*
	*  returned array is not compatible with __construct(array $data=[]).
	*  returned array is compatible with server request format.
	*/
	public function getArray(): array
	{
		$data = [];
		if (!($this->singular === null))
		{
			$data['singular'] = $this->singular->getArray();
		}
		if (!($this->plural === null))
		{
			$data['plural'] = $this->plural->getArray();
		}

		/*if (!($this->gender === null))
			$data['gender'] = $this->gender;*/

		return $data;
	}

	public function singularNominativeExists(): bool
	{
		if ($this->singular === null)
		{
			return false;
		}

		return !empty(trim($this->singular->nominative));
	}
}
