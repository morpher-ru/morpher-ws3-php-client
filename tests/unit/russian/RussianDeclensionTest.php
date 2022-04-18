<?php declare(strict_types=1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../MorpherTestHelper.php";

use Morpher\Ws3Client\InvalidArgumentEmptyString;
use PHPUnit\Framework\TestCase;


use Morpher\Ws3Client\Russian as Russian;



final class RussianDeclensionTest extends TestCase
{
    
    public function testFlags(): void
    {

        $parseResults=[
        ]; 
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $lemma='тест';

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $declensionResult=$testMorpher->russian->Parse($lemma,['flagA','flagB','flagC']);

        $transaction=reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request=$transaction['request'];        

        $uri=$request->getUri();
        $this->assertEquals('/russian/declension',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('s='.rawurlencode($lemma).'&flags='.rawurlencode('flagA,flagB,flagC'),$uri->getQuery());
    
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

        $this->assertInstanceOf(Russian\DeclensionForms::class,$declensionResult->Plural);

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

    public function testParse_ExceptionNoWords(): void
    {
        $this->expectException(Russian\RussianWordsNotFound::class);
        $this->expectExceptionMessage('Не найдено русских слов.');

        $parseResults=[        'code'=>5,
        'message'=> 'Не найдено русских слов.']; 
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text,496);
    
        $lemma='test';
    
        $declensionResult=$testMorpher->russian->Parse($lemma);

    }




    public function testParse_ExceptionNoS(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        $this->expectExceptionMessage('Передана пустая строка.');

        $parseResults=[        'code'=>6,
        'message'=> 'Не указан обязательный параметр: s.'];
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text,400);
    
        $lemma='+++';
    
        $declensionResult=$testMorpher->russian->Parse($lemma);

    }

    public function testParse_ExceptionNoS2(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        $this->expectExceptionMessage('Передана пустая строка.');

        $parseResults=[        'code'=>6,
        'message'=> 'Другое сообщение от сервера']; //с кодом 6 любое сообшение от сервера не учитывается
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text,400);
    
        $lemma='+++';
    
        $declensionResult=$testMorpher->russian->Parse($lemma);

    }

    public function testParse_DeclensionNotSupportedUseSpell(): void
    {
        $this->expectException(Russian\DeclensionNotSupportedUseSpell::class);
        $this->expectExceptionMessage('Склонение числительных в declension не поддерживается. Используйте метод spell.');


        $parseResults=[        'code'=>4,
        'message'=> 'Склонение числительных в declension не поддерживается. Используйте метод spell.']; 
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text,495);
    
        $lemma='двадцать';
    
        $declensionResult=$testMorpher->russian->Parse($lemma);

    }


    public function testParse_InvalidFlags(): void
    {
        $this->expectException(Russian\InvalidFlags::class);
        $this->expectExceptionMessage('Указаны неправильные флаги.');

        $parseResults=[        'code'=>12,
        'message'=> 'Указаны неправильные флаги.']; 
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text,494);
    
        $lemma='двадцать';
    
        $declensionResult=$testMorpher->russian->Parse($lemma,["AAA","BBB"]);

    }






    public function testInvalidJsonResponse(): void
    {
        $return_text='{"И":"тест","Р":"тесте",-}';
        try 
        {

            $lemma='тест';
            $container = [];
            $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text);       
            $declensionResult=$testMorpher->russian->Parse($lemma);
        }
        catch (\Morpher\Ws3Client\InvalidServerResponse $ex)
        {
            $this->assertEquals($ex->response, $return_text);
            return;
        }
        $this->assertTrue(false); //test failed if exception not catched
    
    }

}
