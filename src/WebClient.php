<?php
namespace Morpher\Ws3Client;
require_once __DIR__."/../vendor/autoload.php";


class WebClient 
{
	private string $_url='';
	private string $_token='';	
	private $client;

	public function getToken(): string
	{
		return $this->_token;
	}

	public function __construct($url='https://ws3.morpher.ru',$token='',$timeout=10.0,$handler=null)
	{
		$this->_url=$url;
		$this->_token=$token;

		$this->client=new \GuzzleHttp\Client([
			'base_uri'=>$url,
			'timeout'=>$timeout,
			'handler'=>$handler
			]);	
	}


	public function send_old(HttpRequest $request): string {

		$response=$this->client->request($request->Method, $request->Endpoint, [
			'query' => $request->QueryParameters,
			'headers'=>$request->Headers,
			'http_errors'=>true
		]);

		$result = $response->getBody();

		return $result;
	}

	public function send(string $Endpoint,array $QueryParameters=[],string $Method='GET',array $Headers=[]): string {

		$response=$this->client->request($Method, $Endpoint, [
			'query' => $QueryParameters,
			'headers'=>$Headers,
			'http_errors'=>true
		]);

		$result = $response->getBody();

		return $result;
	}


}