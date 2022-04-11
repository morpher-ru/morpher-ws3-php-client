<?php
namespace Morpher\Ws3Client\Russian;

require_once __DIR__."/../../vendor/autoload.php";
require_once(__DIR__."/../webclientbase.php");

use Morpher\Ws3Client\WebClientBase;
use Morpher\Ws3Client\Russian\DeclensionResult;
class Client
{
	private $webClient;
	
	public function __construct(WebClientBase $webClient)
	{
		$this->webClient=$webClient;
	}
	
	public function Parse(string $lemma)
	{
		if (trim($lemma)=='') throw new \InvalidArgumentException("пустая строка");
		$result_raw = $this->webClient->get_request("/russian/declension", ['s' => $lemma]);
		$result = json_decode($result_raw,true);
		//
		//parse result
/* 		$data['И']=$lemma;
		$data['Р']=$result['Р'];
		$data['Д']=$result['Д'];
		$data['В']=$result['В'];	
		$data['Т']=$result['Т'];	
		$data['П']=$result['П'];
		if (isset($result['П_о']))
			$data['П_о']=$result['П_о'];

		if (isset($result['род']))
			$data['род']=$result['род'];		
	

	
		$data_pl['И']=$result['множественное']['И'];
		$data_pl['Р']=$result['множественное']['Р'];
		$data_pl['Д']=$result['множественное']['Д'];
		$data_pl['В']=$result['множественное']['В'];	
		$data_pl['Т']=$result['множественное']['Т'];	
		$data_pl['П']=$result['множественное']['П'];
		$data_pl['П_о']=$result['множественное']['П_о'];

		$data['где']=$result['где'];
		$data['куда']=$result['куда'];
		$data['откуда']=$result['откуда'];	

		$declensionResult = new DeclensionResult($data,$data_pl);
 */

		$result['И']=$lemma;
		$declensionResult = new DeclensionResult($result);


		return $declensionResult;
	}
	
}