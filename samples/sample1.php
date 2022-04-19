<?php
error_reporting(E_ALL);
require_once __DIR__."/../vendor/autoload.php";
@include_once __DIR__."/../secret.php";//файл секретов есть только локально, на github не выгружаю. отсутствие файла - не ошибка.

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

$base_url = 'https://ws3.morpher.ru';


$token=MORPHER_RU_TOKEN;

$morpher=new Morpher($base_url,$token);

//$rus_dec=$morpher->russian->Parse('Соединенное королевство');
$rus_dec=$morpher->qazaq->Parse('тест');
//$rus_dec=$morpher->russian->Parse('+++');
print_r($rus_dec);
