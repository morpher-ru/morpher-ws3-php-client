<?php

namespace Morpher\Ws3Client;

interface CorrectionEntryInterface
{
    public function SingularNominativeExists(): bool;

    public function getArrayForRequest(): array;

    public function __construct(array $data);
    //public static function CreateEntry(array $data);
}