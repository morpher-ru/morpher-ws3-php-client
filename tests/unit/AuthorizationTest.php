<?php declare(strict_types=1);
require_once __DIR__."/../../vendor/autoload.php";

require_once __DIR__."/MorpherTestHelper.php";


use PHPUnit\Framework\TestCase;
use Morpher\Ws3Client\Russian as Russian;
use Morpher\Ws3Client\Ukrainian as Ukrainian;

class AuthorizationTest extends TestCase
{

    public function CallbacksProvider():array
    {
        return [  //список функций для прогонки через тесты [текст ответа (json), функция вызова запроса]
            ['GET','{}'                   ,function ($testMorpher)    {     $testMorpher->russian->Parse('тест');     }],//dataset #0
            ['GET','{}'                   ,function ($testMorpher)    {     $testMorpher->qazaq->Parse('тест');       }],//dataset #1
            ['GET','{"n":[],"unit":[]}'   ,function ($testMorpher)    {     $testMorpher->russian->Spell(10,'тест');  }],//dataset #2
            ['GET','{}'                   ,function ($testMorpher)    {     $testMorpher->russian->SpellDate('1988-07-01');  }],//dataset #3
            ['GET','{"n":[],"unit":[]}'   ,function ($testMorpher)    {     $testMorpher->russian->SpellOrdinal(10,'тест');  }],//dataset #4
            ['GET','{}'                   ,function ($testMorpher)    {     $testMorpher->russian->AdjectiveGenders("уважаемый");  }],//dataset #5
            ['GET','[]'                   ,function ($testMorpher)    {     $testMorpher->russian->Adjectivize("мытыщи");  }],//dataset #6
            ['POST','"тест"'              ,function ($testMorpher)    {     $testMorpher->russian->AddStressmarks("тест");  }],//dataset #7
            ['GET','[]'                   ,function ($testMorpher)    {     $testMorpher->russian->userDict->GetAll();     }],//dataset #8
            ['POST','[]'                  ,function ($testMorpher)    {     $testMorpher->russian->userDict->AddOrUpdate(new Russian\CorrectionEntry(['singular'=>['И'=>'чебуратор','Р'=>'чебурыла']]));     }],//dataset #9
            ['DELETE','[]'                ,function ($testMorpher)    {     $testMorpher->russian->userDict->Remove('чебуратор');     }],//dataset #10
            ['GET','[]'                   ,function ($testMorpher)    {     $testMorpher->ukrainian->Parse('тест');     }],//dataset #11       
            ['GET','[]'                   ,function ($testMorpher)    {     $testMorpher->ukrainian->userDict->GetAll();     }],//dataset #12
            ['POST','[]'                  ,function ($testMorpher)    {     $testMorpher->ukrainian->userDict->AddOrUpdate(new Ukrainian\CorrectionEntry(['singular'=>['Н'=>'чебуратор','Р'=>'чебурыла']]));     }],//dataset #13
            ['DELETE','[]'                ,function ($testMorpher)    {     $testMorpher->ukrainian->userDict->Remove('чебуратор');     }],//dataset #14
            ['GET','111'                  ,function ($testMorpher)    {     $testMorpher->getQueriesLeftForToday();     }],//dataset #15

        ];
    }

     /**
     * @dataProvider  CallbacksProvider
     */    
    public function testAuthorization(string $method,string $requestResult, callable $callback): void
    {
        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$requestResult);
        
        $callback($testMorpher);

        $this->assertAuthorization(reset($container)['request'],$method);
    }


    /**
     * @dataProvider  CallbacksProvider
     */ 
    public function testNoToken(string $method,string $requestResult,callable $callback): void
    {
        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$requestResult,200,'');
        
        $callback($testMorpher);

        $this->assertAuthorizationNoToken(reset($container)['request'],$method);
    }

    /**
     * @dataProvider  CallbacksProvider
     */ 
    public function testServerError500(string $method,string $requestResult,callable $callback): void
    {
        $this->expectException(\GuzzleHttp\Exception\ServerException::class);
        $this->expectExceptionMessage('Error 500');
    

        $testMorpher=MorpherTestHelper::createMockMorpherWithException(new \GuzzleHttp\Exception\ServerException(
            'Error 500', 
            new \GuzzleHttp\Psr7\Request($method, 'test'), 
            new \GuzzleHttp\Psr7\Response(500,[],'')
        ));

        $callback($testMorpher);
    }


    /**
     * @dataProvider  CallbacksProvider
     */ 
    public function testIpBlocked(string $method,string $requestResult,callable $callback): void
    {
        $this->expectException(\Morpher\Ws3Client\IpBlocked::class);
        $this->expectExceptionMessage('IP заблокирован.');

        $parseResults=[        'code'=>3,
        'message'=> 'IP заблокирован.']; 
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);
        $container = [];
        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text,444);

        $callback($testMorpher);
    }

    /**
     * @dataProvider  CallbacksProvider
     */ 
    public function testParse_NetworkError1(string $method,string $requestResult,callable $callback): void
    {
        $this->expectException(\GuzzleHttp\Exception\ConnectException::class);
        $testMorpher=MorpherTestHelper::createMockMorpherWithException(new \GuzzleHttp\Exception\ConnectException('connection cannot be established', new \GuzzleHttp\Psr7\Request($method, 'test')));
        $callback($testMorpher);
    }

    /**
     * @dataProvider  CallbacksProvider
     */ 
    public function testParse_NetworkError2(string $method,string $requestResult,callable $callback): void
    {
        $this->expectException(\GuzzleHttp\Exception\RequestException::class);
        $testMorpher=MorpherTestHelper::createMockMorpherWithException(new \GuzzleHttp\Exception\RequestException('connection cannot be established', new \GuzzleHttp\Psr7\Request($method, 'test')));
        $callback($testMorpher);
    }

    /**
     * @dataProvider  CallbacksProvider
     */ 
    public function testParse_UnknownError(string $method,string $requestResult,callable $callback): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidServerResponse::class);
        $this->expectExceptionMessage('Неизвестный код ошибки');

        $parseResults=[        'code'=>100,
        'message'=> 'Непонятная ошибка.']; 
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text,444);
    
        $callback($testMorpher);
    }

    /**
     * @dataProvider  CallbacksProvider
     */ 
    public function testParse_InvalidServerResponse(string $method,string $requestResult,callable $callback): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidServerResponse::class);

        $parseResults=[]; //если пустое тело сообщения об ошибке
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text,496);
    
        $callback($testMorpher);
    }

    public function assertAuthorization(\Psr\Http\Message\RequestInterface $request,string $method='GET'):void
    {    
        $this->assertEquals($method, $request->getMethod());   
        $this->assertTrue($request->hasHeader('Accept'));
        $this->assertEquals(["application/json"], $request->getHeaders()['Accept']);
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertEquals(["Basic ".base64_encode('testtoken')], $request->getHeaders()['Authorization']);
    }

    public function assertAuthorizationNoToken(\Psr\Http\Message\RequestInterface $request,string $method='GET'):void
    {
        $this->assertEquals($method, $request->getMethod());   
        $this->assertTrue($request->hasHeader('Accept'));
        $this->assertEquals(["application/json"], $request->getHeaders()['Accept']);
        $this->assertFalse($request->hasHeader('Authorization'));
    }
}