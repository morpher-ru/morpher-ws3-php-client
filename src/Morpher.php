<?php
namespace Morpher\Ws3Client;

require_once __DIR__."/../vendor/autoload.php";


class Morpher
{
	public readonly Russian\Client $russian;
	public readonly Qazaq\Client $qazaq;
	
	private readonly WebClient $_webClient;
	
	public function __construct(string $url='https://ws3.morpher.ru',string $token='',float$timeout=10.0,$handler=null)
	{
		$this->_webClient=new WebClient($url,$token,$timeout,$handler);
		$this->russian=new Russian\Client($this->_webClient);
		$this->qazaq=new Qazaq\Client($this->_webClient);
	}
}