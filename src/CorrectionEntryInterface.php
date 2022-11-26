<?php

namespace Morpher\Ws3Client;

interface CorrectionEntryInterface
{
	public function __construct(array $data);

	public function singularNominativeExists(): bool;

	public function getArrayForRequest(): array;
	//public static function createEntry(array $data);
}
