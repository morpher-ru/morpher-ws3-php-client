<?php declare(strict_types = 1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../MorpherTestHelper.php";

use Morpher\Ws3Client\InvalidArgumentEmptyString;
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\Russian as Russian;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use Morpher\Ws3Client\Russian\CorrectionEntry;
use Morpher\Ws3Client\WebClient;

final class RussianUserDictTest extends TestCase
{
    public function testUserDictGet_Success(): void
    {
        $parseResults = [
                [
                    "singular" => [
                        "И" => "Кошка",
                        "Р" => "Пантеры",
                        "Д" => "Пантере",
                        "В" => "Пантеру",
                        "Т" => "Пантерой",
                        "П" => "о Пантере",
                        "М" => "в Пантере"
                    ],
                    "plural" => [
                        "И" => "Пантеры",
                        "Р" => "Пантер",
                        "Д" => "Пантерам",
                        "В" => "Пантер",
                        "Т" => "Пантерами",
                        "П" => "о Пантерах",
                        "М" => "в Пантерах"
                    ]
                ]
        ];

        $return_text = json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $list = $testMorpher->russian->userDict->getAll();

        $transaction = reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request = $transaction['request'];

        $this->assertEquals('GET',$request->getMethod());

        $uri = $request->getUri();
        $this->assertEquals('/russian/userdict',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('',$uri->getQuery());

        $this->assertNotNull($list);
        $this->assertIsArray($list);
        $this->assertCount(1,$list);

        $this->assertInstanceOf(CorrectionEntry::class, $list[0]);
        $entry = $list[0];

        $this->assertEquals("Кошка",$entry->Singular->Nominative);
        $this->assertEquals("Пантеры",$entry->Singular->Genitive);
        $this->assertEquals("Пантере",$entry->Singular->Dative);
        $this->assertEquals("Пантеру",$entry->Singular->Accusative);
        $this->assertEquals("Пантерой",$entry->Singular->Instrumental);
        $this->assertEquals("о Пантере",$entry->Singular->Prepositional);
        $this->assertEquals("в Пантере",$entry->Singular->Locative);

        $this->assertEquals("Пантеры",$entry->Plural->Nominative);
        $this->assertEquals("Пантер",$entry->Plural->Genitive);
        $this->assertEquals("Пантерам",$entry->Plural->Dative);
        $this->assertEquals("Пантер",$entry->Plural->Accusative);
        $this->assertEquals("Пантерами",$entry->Plural->Instrumental);
        $this->assertEquals("о Пантерах",$entry->Plural->Prepositional);
        $this->assertEquals("в Пантерах",$entry->Plural->Locative);        
    }

    public function testUserDictRemove_Success(): void
    {
        $return_text = 'true';

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);
        
        $lemma = 'кошка';
        $testMorpher->russian->userDict->remove($lemma);

        $transaction = reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request = $transaction['request'];        
        $this->assertEquals('DELETE',$request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/russian/userdict',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('s='.  rawurlencode($lemma) ,$uri->getQuery());
    }


    public function testUserDictRemoveEmpty_Exception(): void
    {
        $message = 'Передана пустая строка.';

        $this->expectException(InvalidArgumentEmptyString::class);
        $this->expectExceptionMessage($message);

        $parseResults = [
            "code" => 6,
            "message" => $message
        ];

        $return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text, 400);
        
        $testMorpher->russian->userDict->remove('');
    }

    public function testUserDicPost_Success(): void
    {
        $return_text = '';

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $correction = new CorrectionEntry();
        $correction->Singular->Nominative = 'чебуратор';
        $correction->Singular->Locative = 'в чебураторе';
        $correction->Plural->Locative = 'в чебураторах';

        $testMorpher->russian->userDict->AddOrUpdate($correction);

        $transaction = reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request = $transaction['request'];        
        $this->assertEquals('POST',$request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/russian/userdict',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEmpty($uri->getQuery());
        $this->assertEquals(urlencode('И').'='.urlencode('чебуратор').'&'.urlencode('М').'='.urlencode('в чебураторе').'&'.urlencode('М_М').'='.urlencode('в чебураторах') ,(string)$request->getBody());
    }

    public function testUserDicPost_Success2(): void
    {
        $return_text = '';

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $correction = new CorrectionEntry();
        $correction->Singular->Nominative = 'чебуратор';
        $correction->Plural->Locative = 'в чебураторах';

        $testMorpher->russian->userDict->AddOrUpdate($correction);

        $transaction = reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request = $transaction['request'];        
        $this->assertEquals('POST',$request->getMethod());
        $uri = $request->getUri();
        $this->assertEquals('/russian/userdict',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEmpty($uri->getQuery());
        $this->assertEquals(  urlencode('И').'='.  urlencode('чебуратор').'&'.  urlencode('М_М').'='.  urlencode('в чебураторах') ,(string)$request->getBody());
    }

    public function testUserDicPost_Exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Обязательно должен быть указан именительный падеж единственного числа.');

        $parseResults = [
        ];

        $return_text = json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $correction = new CorrectionEntry();
        //$correction->Singular->Nominative = 'чебуратор';
        $correction->Singular->Locative = 'в чебураторе';
        $correction->Plural->Locative = 'в чебураторах';
 
        $testMorpher->russian->userDict->AddOrUpdate($correction);
    }

    public function testUserDicPost_Exception2(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Нужно указать хотя бы одну косвенную форму.');

        $parseResults = [
        ];

        $return_text = json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $correction = new CorrectionEntry();
        $correction->Singular->Nominative = 'чебуратор';
        //$correction->Singular->Locative = 'в чебураторе';
        //$correction->Plural->Locative = 'в чебураторах';
 
        $testMorpher->russian->userDict->AddOrUpdate($correction);
    }
}
