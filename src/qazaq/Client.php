<?php
namespace Morpher\Ws3Client\Qazaq;

require_once __DIR__."/../../vendor/autoload.php";
require_once(__DIR__."/../webclientbase.php");

use Morpher\Ws3Client\WebClientBase;
use Morpher\Ws3Client\Qazaq\DeclensionResult;
class Client
{
	private $webClient;
	
	public function __construct(WebClientBase $webClient)
	{
		$this->webClient=$webClient;
	}
	
	public function Parse(string $lemma)
	{
		if (trim($lemma)=='') throw new ValueError("пустая строка");
		$result_raw = $this->webClient->get_request("/qazaq/declension", ['s' => $lemma]);
		$result = json_decode($result_raw,true);


		$data['A']=$lemma;	
		//
		//parse result
		$data['І']=$result['І'];
		$data['Б']=$result['Б'];
		$data['Т']=$result['Т'];
		$data['Ш']=$result['Ш'];	
		$data['Ж']=$result['Ж'];	
		$data['К']=$result['К'];
	
	
		$data_pl['A']=$result['көпше']['A'];
		$data_pl['І']=$result['көпше']['І'];
		$data_pl['Б']=$result['көпше']['Б'];
		$data_pl['Т']=$result['көпше']['Т'];	
		$data_pl['Ш']=$result['көпше']['Ш'];	
		$data_pl['Ж']=$result['көпше']['Ж'];
		$data_pl['К']=$result['көпше']['К'];


		$declensionResult = new DeclensionResult($data,$data_pl);

		return $declensionResult;
	}
	
}