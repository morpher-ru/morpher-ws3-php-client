<?php
namespace Morpher\Ws3Client;



class Morpher
{
	public Russian\Client $russian;
	public Qazaq\Client $qazaq;
	public Ukrainian\Client $ukrainian;
	private WebClient $_webClient;
	
	public function __construct(string $url='https://ws3.morpher.ru',string $token='',float $timeout=10.0, $handler=null)
	{
		$this->_webClient=new WebClient($url,$token,$timeout,$handler);
		$this->russian=new Russian\Client($this->_webClient);
		$this->qazaq=new Qazaq\Client($this->_webClient);
		$this->ukrainian=new Ukrainian\Client($this->_webClient);
	}

	public function getQueriesLeftForToday():int
	{
		try
        {
			$result_raw=$this->_webClient->send("/get_queries_left_for_today");
		}
		catch (\Morpher\Ws3Client\MorpherError $ex)
		{
			throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
		}

		$result=WebClient::JsonDecode($result_raw);

		return (int)$result;		
	}
}