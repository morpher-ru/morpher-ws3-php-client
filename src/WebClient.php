<?php
namespace Morpher\Ws3Client;
require_once __DIR__."/../vendor/autoload.php";
require_once("WebClientBase.php");


class WebClient extends WebClientBase
{
	private string $_url='';
	private string $_token='';	
	private $client;

	public function getToken(): string
	{
		return $this->_token;
	}

	public function __construct($url='https://ws3.morpher.ru',$token='',$timeout=10.0)
	{
		$this->_url=$url;
		$this->_token=$token;

		$this->client=new \GuzzleHttp\Client([
			'base_uri'=>$url,
			'timeout'=>$timeout
			]);	
	}


	public function send(HttpRequest $request): string {

		$response=$this->client->request($request->Method, $request->Endpoint, [
			'query' => $request->QueryParameters,
			'headers'=>$request->Headers,
			'http_errors'=>true
		]);

		$result = $response->getBody();

		return $result;
	}

}