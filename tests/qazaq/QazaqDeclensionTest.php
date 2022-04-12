<?php declare(strict_types=1);

require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/../../src/Morpher.php";
require_once __DIR__."/../../src/WebClient.php";

use PHPUnit\Framework\TestCase;

//use Morpher\Ws3Client\Morpher;


use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;

use Morpher\Ws3Client\WebClient;

use Morpher\Ws3Client\Qazaq as Qazaq;

final class QazaqDeclensionTest extends TestCase
{

    public function testQazaqDeclension(): void
    {

        $parseResults=[
            "І"=> "тесттің",
            "Б"=> "тестке",
            "Т"=> "тестті",
            "Ш"=> "тесттен",
            "Ж"=> "тестте",
            "К"=> "тестпен",    
            "көпше"=> [
                "A"=> "тесттер",
                "І"=> "тесттертің",
                "Б"=> "тесттерке",
                "Т"=> "тесттерті",
                "Ш"=> "тесттертен",
                "Ж"=> "тесттерте",
                "К"=> "тесттерпен"
            ]
        ];
        
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);
        



    
        $lemma='тест';



        $mock = new MockHandler([
            new Response(200, [], $return_text)
        ]);

        
        $handlerStack = HandlerStack::create($mock);
        
        $container = [];
        $history = Middleware::history($container);
        // Add the history middleware to the handler stack.
        $handlerStack->push($history);
                
        $webClientMock=new WebClient('https://test.uu','testtoken',10,$handlerStack);
  
        $testMorpher=new Morpher\Ws3Client\Morpher($webClientMock);
        
        $declensionResult=$testMorpher->qazaq->Parse($lemma);

        $transaction=reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request=$transaction['request'];        
        $this->assertEquals("GET", $request->getMethod());   
        $this->assertTrue($request->hasHeader('Accept'));
        $this->assertEquals(["application/json"], $request->getHeaders()['Accept']);
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertEquals(["Basic testtoken"], $request->getHeaders()['Authorization']);
        $uri=$request->getUri();
        $this->assertEquals('/qazaq/declension',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('s='.rawurlencode($lemma),$uri->getQuery());


        $this->assertInstanceOf(Qazaq\DeclensionForms::class ,$declensionResult);
        //Assert.IsNotNull($declensionResult);
        $this->assertInstanceOf(Qazaq\SameNumberForms::class,$declensionResult->Plural);


        $this->assertEquals("тест", $declensionResult->Nominative);
        $this->assertEquals("тесттің", $declensionResult->Genitive);
        $this->assertEquals("тестке", $declensionResult->Dative);
        $this->assertEquals("тестті", $declensionResult->Accusative);
        $this->assertEquals("тесттен", $declensionResult->Ablative);
        $this->assertEquals("тестте", $declensionResult->Locative);
        $this->assertEquals("тестпен", $declensionResult->Instrumental);

        $this->assertEquals("тесттер", $declensionResult->Plural->Nominative);
        $this->assertEquals("тесттертің", $declensionResult->Plural->Genitive);
        $this->assertEquals("тесттерке", $declensionResult->Plural->Dative);
        $this->assertEquals("тесттерті", $declensionResult->Plural->Accusative);
        $this->assertEquals("тесттертен", $declensionResult->Plural->Ablative);
        $this->assertEquals("тесттерте", $declensionResult->Plural->Locative);
        $this->assertEquals("тесттерпен", $declensionResult->Plural->Instrumental);


    }


}
