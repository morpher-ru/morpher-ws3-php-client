<?php
namespace Morpher\Ws3Client;
require_once __DIR__."/../vendor/autoload.php";
require_once("WebClientBase.php");


class WebClient extends WebClientBase
{
	private $_url='';
	private $_token='';	
	public function __construct($url='https://ws3.morpher.ru',$token='')
	{
		$this->_url=$url;
		$this->_token=$token;
	}
	
	public function get_request($endpoint, $params = NULL) {
		$url=$this->_url . $endpoint;
		//$url='';
		$ch = curl_init();

		if ($params !== NULL && !empty($params)){
			$url .= '?';
			foreach($params as $key => $value) {
				$url .= $key . '=' . curl_escape($ch, $value) . '&';
			}
			$url = rtrim($url, '&');
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,
					CURLOPT_HTTPHEADER,
					array('Accept: application/json',
						  'Authorization: Basic '.$this->_token));
		$result = curl_exec($ch);
		if ($result === false) { $result = curl_error($ch); }
		//$json = json_decode($result,true);
		curl_close($ch);
		return $result;
	}
	
	public function post_raw($endpoint, $body) {
		$url=$this->url.$endpoint;
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch,
					CURLOPT_HTTPHEADER,
					array('Content-Type: text/plain',
						  'Accept: application/json',
						  'Authorization: Basic '.$this->_token));
		$result = curl_exec($ch);
		if ($result === false) { $result = curl_error($ch); }
		//$json = json_decode($result);
		curl_close($ch);
		return $result;     
	}	
}