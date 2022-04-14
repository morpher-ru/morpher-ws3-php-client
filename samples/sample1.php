<?php
error_reporting(E_ALL);
require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../src/WebClient.php";
require_once __DIR__."/../src/Morpher.php";

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

$base_url = 'https://ws3.morpher.ru';

$token="xxx";
$webClient=new WebClient($base_url,$token);
$morpher=new Morpher($webClient);

$rus_dec=$morpher->russian->Parse('Соединенное королевство');
//$rus_dec=$morpher->russian->Parse('+++');
print_r($rus_dec);
