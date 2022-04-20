<?php
namespace Morpher\Ws3Client\Russian;

require_once __DIR__."/../../vendor/autoload.php";

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\UserDictBase;


class UserDict extends UserDictBase 
{
	function __construct(WebClient $webClient)
	{
		parent::__construct($webClient,'/russian/userdict', CorrectionEntry::class);
	}

	public function AddOrUpdate(CorrectionEntry $entry): void
	{
		$this->AddOrUpdateBase($entry);

	}
}
