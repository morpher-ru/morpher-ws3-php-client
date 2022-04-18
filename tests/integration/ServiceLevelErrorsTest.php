<?php declare(strict_types=1);

require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/IntegrationBase.php";

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

final class ServiceLevelErrorsTest extends TestCase
{
    public function CallbacksProvider():array
    {
        return [  //список функций для прогонки через тесты
            [function ($testMorpher)    {     $testMorpher->russian->Parse('тест');     }],//dataset #0
            [function ($testMorpher)    {     $testMorpher->qazaq->Parse('тест');       }],//dataset #1
        ];
    }

    /**
     * @dataProvider  CallbacksProvider
     */    
    public function testTokenIncorrectFormatError(callable $callback): void
    {
        $token='23525555555555555555555555555555555555555555555555';// incorrect format token

        $testMorpher=new Morpher(IntegrationBase::BASE_URL,$token);     

        $this->expectException(\Morpher\Ws3Client\TokenIncorrectFormat::class);

        $callback($testMorpher);
    }

    /**
     * @dataProvider  CallbacksProvider
     */ 
    public function testTokenNotFoundError(callable $callback): void
    {
        $token='41e2111a-767b-4a07-79A3-d52c02cb5a0d';// not existing token, valid length
 
        $testMorpher=new Morpher(IntegrationBase::BASE_URL,$token);     

        $this->expectException(\Morpher\Ws3Client\TokenNotFound::class);

        $callback($testMorpher);
    }

    /**
     * @dataProvider  CallbacksProvider
     */ 
    public function testTimeoutError(callable $callback): void
    {
 
        //not existing ip, timeout in 0.1 sec
        $testMorpher=new Morpher('http://10.200.200.200','',0.1);     

        $this->expectException(\GuzzleHttp\Exception\ConnectException::class);

        $callback($testMorpher);
    }
}