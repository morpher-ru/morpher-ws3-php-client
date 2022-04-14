<?php declare(strict_types=1);
require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/../../src/Morpher.php";
require_once __DIR__."/../../src/WebClient.php";
require_once __DIR__."/../../src/russian/Gender.php";
//require_once __DIR__."/../MorpherTestHelper.php";
require_once __DIR__."/../../src/exceptions/MorpherError.php";
require_once __DIR__."/../../src/exceptions/IpBlocked.php";
require_once __DIR__."/../../src/russian/exceptions/InvalidFlags.php";
require_once __DIR__."/../../src/russian/exceptions/DeclensionNotSupportedUseSpell.php";
require_once __DIR__."/../../src/exceptions/InvalidArgumentEmptyString.php";
require_once __DIR__."/../../src/exceptions/InvalidServerResponse.php";
require_once __DIR__."/../../src/russian/exceptions/RussianWordsNotFound.php";

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

//use Morpher\Ws3Client\Morpher;
use Morpher\Ws3Client\Russian as Russian;



final class RussianDeclensionIntegrationTest extends TestCase
{
    public function testSimple(): void
    {
        $token='';
        foreach (getenv() as $settingKey => $settingValue) {
            /*if (strpos($settingKey, 'MORPHER_') === 0) {
                ...
            }
            print ($settingKey.' = '.$settingValue."\r\n");
*/
            if ($settingKey=='MORPHER_RU_TOKEN')
            {
                $token=$settingValue;
                break;
            }
        }
        $this->assertNotEmpty($token);    

 
        
        $base_url = 'https://ws3.morpher.ru';
        $webClient=new WebClient($base_url,$token);
        $morpher=new Morpher($webClient);
        
        $rus_dec=$morpher->russian->Parse('Соединенное королевство');
        //$rus_dec=$morpher->russian->Parse('+++');
        print_r($rus_dec);
        $this->assertTrue(true);    
    }

}