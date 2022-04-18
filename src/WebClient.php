<?php
namespace Morpher\Ws3Client;
require_once __DIR__."/../vendor/autoload.php";

use GuzzleHttp\Exception\ClientException;

class WebClient
{
	private string $_token='';	
	private string $_tokenBase64='';	
	private \GuzzleHttp\Client $client;

	public function __construct($url='https://ws3.morpher.ru',$token='',$timeout=10.0,$handler=null)
	{
		$this->_token=$token;
		$this->_tokenBase64=base64_encode($token);

		$this->client=new \GuzzleHttp\Client([
			'base_uri'=>$url,
			'timeout'=>$timeout,
			'handler'=>$handler
			]);	
	}

	public function getStandartHeaders():array
	{
		$headers=['Accept'=> 'application/json'];

		if (!empty($this->_tokenBase64))
		{
			$headers['Authorization']= 'Basic '.$this->_tokenBase64;

		}
		return $headers;
	}

	public function send(string $Endpoint,$QueryParameters=[],string $Method='GET',$Headers=null,$body=null,$form_params=null): string
    {
		if ($Headers===null)
		{
			$Headers=$this->getStandartHeaders();
		}
		try
		{
			$response=$this->client->request($Method, $Endpoint, [
				'query' => $QueryParameters,
				'headers'=>$Headers,
				'http_errors'=>true,
				'body'=>$body,
				'form_params'=>$form_params
			]);

			$result = $response->getBody();
		}
		catch (ClientException $ex)
		{
			if ($ex->hasResponse())
			{
				$response=$ex->getResponse();
				$code=$response->getStatusCode();
				if ($code>=400)
				{
					$data=json_decode($response->getBody(),true);
					if (!isset($data['message']) || empty($data['message']))
						throw new InvalidServerResponse();
					if (!isset($data['code']) || empty($data['code']))
						throw new InvalidServerResponse();
					
					$msg=(string)($data['message'] ?? "Неизвестная ошибка");
					$morpher_code=(int)($data['code'] ?? $code);

					if ($morpher_code==6) throw new InvalidArgumentEmptyString();
					if ($morpher_code==1) throw new RequestsDailyLimit($data['message']);
					if ($morpher_code==3) throw new IpBlocked($data['message']);
					if ($morpher_code==9) throw new TokenNotFound($data['message']);
					if ($morpher_code==10) throw new TokenIncorrectFormat($data['message']);
					if ($morpher_code==25) throw new TokenRequired($data['message']);

					throw new MorpherError($msg,$morpher_code);
				}
			}

			throw $ex;
		}

		return $result;
	}

	public static function JsonDecode(string $text):mixed
	{
		try
		{
			return json_decode($text,true,512,JSON_THROW_ON_ERROR);
		}
		catch (\JsonException $ex)
		{
			throw new \Morpher\Ws3Client\InvalidServerResponse("Некорректный JSON ответ от сервера",$text);
		}
	}


}