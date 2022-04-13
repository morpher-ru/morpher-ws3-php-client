<?php declare(strict_types=1);
require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/../../src/Morpher.php";
require_once __DIR__."/../../src/WebClient.php";
require_once __DIR__."/../../src/russian/Gender.php";
require_once __DIR__."/../MorpherTestHelper.php";

use PHPUnit\Framework\TestCase;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;

use Morpher\Ws3Client\WebClient;
//use Morpher\Ws3Client\Morpher;
use Morpher\Ws3Client\Russian as Russian;



final class RussianDeclensionTest extends TestCase
{




    
    public function testAuthorizarion(): void
    {

        $parseResults=[
        ]; 
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $lemma='тест';

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $declensionResult=$testMorpher->russian->Parse($lemma);

        $transaction=reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request=$transaction['request'];        
        $this->assertEquals("GET", $request->getMethod());   
        $this->assertTrue($request->hasHeader('Accept'));
        $this->assertEquals(["application/json"], $request->getHeaders()['Accept']);
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertEquals(["Basic testtoken"], $request->getHeaders()['Authorization']);

    
    }

    public function testParse_Success(): void
    {

        $parseResults=[
            "Р"=> "теста",
            "Д"=> "тесту",
            "В"=> "тест",
            "Т"=> "тестом",
            "П"=> "тесте",
            "П_о"=> "о тесте",
            "род"=> "Мужской",
            "множественное"=> [
                "И"=> "тесты",
                "Р"=> "тестов",
                "Д"=> "тестам",
                "В"=> "тесты",
                "Т"=> "тестами",
                "П"=> "тестах",
                "П_о"=>"о тестах"
            ],
            "где"=> "в тесте",
            "куда"=> "в тест",
            "откуда"=> "из теста"
        ]; 
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $lemma='тест';


        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $declensionResult=$testMorpher->russian->Parse($lemma);


        $transaction=reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request=$transaction['request'];        

        $uri=$request->getUri();
        $this->assertEquals('/russian/declension',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('s='.rawurlencode($lemma),$uri->getQuery());

        $this->assertInstanceOf(Russian\DeclensionForms::class ,$declensionResult);
        //Assert.IsNotNull($declensionResult);
        $this->assertInstanceOf(Russian\DeclensionResult::class,$declensionResult);


        $this->assertEquals("тест", $declensionResult->Nominative);
        $this->assertEquals("теста", $declensionResult->Genitive);
        $this->assertEquals("тесту", $declensionResult->Dative);
        $this->assertEquals("тест", $declensionResult->Accusative);
        $this->assertEquals("тестом", $declensionResult->Instrumental);
        $this->assertEquals("тесте", $declensionResult->Prepositional);
        
        $this->assertEquals("о тесте", $declensionResult->PrepositionalWithO);

        $this->assertEquals("в тесте", $declensionResult->Where);
        $this->assertEquals("в тест", $declensionResult->To);
        $this->assertEquals("из теста", $declensionResult->From);

        $this->assertEquals("тесты", $declensionResult->Plural->Nominative);
        $this->assertEquals("тестов", $declensionResult->Plural->Genitive);
        $this->assertEquals("тестам", $declensionResult->Plural->Dative);
        $this->assertEquals("тесты", $declensionResult->Plural->Accusative);
        $this->assertEquals("тестами", $declensionResult->Plural->Instrumental);
        $this->assertEquals("тестах", $declensionResult->Plural->Prepositional);
        $this->assertEquals("о тестах", $declensionResult->Plural->PrepositionalWithO);

        $this->assertEquals(Russian\Gender::Masculine, $declensionResult->Gender);

    }


   

    public function testSplitFio_Success(): void
    {


        $parseResults=[
            "Р"=> "Александра Пушкина",
            "Д"=> "Александру Пушкину",
            "В"=> "Александра Пушкина",
            "Т"=> "Александром Пушкиным",
            "П"=> "Александре Пушкине",
            "ФИО"=> [
              "Ф"=> "Пушкин",
              "И"=> "Александр",
              "О"=> "Сергеевич"
            ]
        ]; 
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $lemma="Александр Пушкин Сергеевич";

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $declensionResult=$testMorpher->russian->Parse($lemma);


        $transaction=reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request=$transaction['request'];        

        $uri=$request->getUri();
        $this->assertEquals('/russian/declension',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('s='.rawurlencode($lemma),$uri->getQuery());

        $this->assertInstanceOf(Russian\DeclensionForms::class ,$declensionResult);
        //Assert.IsNotNull($declensionResult);
        $this->assertNotNull($declensionResult->FullName);
        $this->assertInstanceOf(Russian\FullName::class ,$declensionResult->FullName);        
        $this->assertEquals("Пушкин", $declensionResult->FullName->Surname);
        $this->assertEquals("Александр", $declensionResult->FullName->Name);
        $this->assertEquals("Сергеевич", $declensionResult->FullName->Pantronymic);
    }

    public function testParse_Exception(): void
    {
        $this->expectException(InvalidArgumentException::class);


        $parseResults=[]; 
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text);
    
        $lemma='';
    
        $declensionResult=$testMorpher->russian->Parse($lemma);

    }

    public function testNullGenitive(): void
    {

        $parseResults=[

            "Д"=> "теляти",
            "В"=> "теля",
  
        ]; 
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);
        $lemma='теля';
    
        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $genitive = $testMorpher->russian->Parse($lemma)->Genitive;


        $transaction=reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request=$transaction['request'];        

        $uri=$request->getUri();
        $this->assertEquals('/russian/declension',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('s='.rawurlencode($lemma),$uri->getQuery());

    

        $this->assertNull($genitive);
    }

}
