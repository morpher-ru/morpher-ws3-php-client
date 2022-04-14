<?php declare(strict_types=1);
require_once __DIR__."/../vendor/autoload.php";

@include_once __DIR__."/../secret.php";//файл секретов есть только локально, на github не выгружаю. отсутствие файла - не ошибка.

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

//use Morpher\Ws3Client\Morpher;
use Morpher\Ws3Client\Russian as Russian;



class IntegrationBase extends TestCase
{
    const BASE_URL='https://ws3.morpher.ru';
    protected static string $token='';
    protected static $webClient;
    protected static $testMorpher;

    public static function setUpBeforeClass(): void
    {
        $token='';

        if (defined("MORPHER_RU_TOKEN"))
        {//если токен задан константой
            $token=MORPHER_RU_TOKEN;
        } else
        foreach (getenv() as $settingKey => $settingValue) { //если токен задан в секретах на github
            if ($settingKey=='MORPHER_RU_TOKEN')
            {
                $token=$settingValue;
                break;
            }
        }

 
        if (empty($token)) throw new Exception('Secret token not found or empty. Tests will not run.');
        //if (empty($token)) $token="";    

        self::$token=$token;

        self::$webClient=new WebClient(self::BASE_URL,self::$token);
        self::$testMorpher=new Morpher(self::$webClient);        

    }


}