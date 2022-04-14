<?php declare(strict_types=1);
require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../src/Morpher.php";
require_once __DIR__."/../src/WebClient.php";

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
        foreach (getenv() as $settingKey => $settingValue) {
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