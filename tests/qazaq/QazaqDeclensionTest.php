<?php declare(strict_types=1);

require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/../../src/Morpher.php";
require_once __DIR__."/../../src/WebClient.php";
require_once __DIR__."/../MorpherTestHelper.php";

require_once __DIR__."/../../src/exceptions/MorpherError.php";
require_once __DIR__."/../../src/exceptions/EmptyString.php";
require_once __DIR__."/../../src/exceptions/InvalidServerResponse.php";
use PHPUnit\Framework\TestCase;

//use Morpher\Ws3Client\Morpher;



use Morpher\Ws3Client\WebClient;

use Morpher\Ws3Client\Qazaq as Qazaq;

final class QazaqDeclensionTest extends TestCase
{
    public function testAuthorization(): void
    {

        $parseResults=[
        ]; 
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $lemma='тест';


        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $declensionResult=$testMorpher->qazaq->Parse($lemma);


        $transaction=reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request=$transaction['request'];        
        $this->assertEquals("GET", $request->getMethod());   
        $this->assertTrue($request->hasHeader('Accept'));
        $this->assertEquals(["application/json"], $request->getHeaders()['Accept']);
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertEquals(["Basic testtoken"], $request->getHeaders()['Authorization']);

    
    }
    public function testQazaqParse_Success(): void
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

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $declensionResult=$testMorpher->qazaq->Parse($lemma);

        $transaction=reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request=$transaction['request'];        

        $uri=$request->getUri();
        $this->assertEquals('/qazaq/declension',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('s='.rawurlencode($lemma),$uri->getQuery());


        $this->assertInstanceOf(Qazaq\DeclensionForms::class ,$declensionResult);
        //$this->assertNotNull($declensionResult);
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


    public function testQazaqParse_Personal_Success():void
    {





        $parseResults=[
            "A"=> "бала",
            "І"=> "баланың",
            "Б"=> "балаға",
            "Т"=> "баланы",
            "Ш"=> "баладан",
            "Ж"=> "балада",
            "К"=> "баламен",
            "менің"=> [
                "A"=> "балам",
                "І"=> "баламның",
                "Б"=> "балама",
                "Т"=> "баламды",
                "Ш"=> "баламнан",
                "Ж"=> "баламда",
                "К"=> "баламмен"
            ],
            "сенің"=> [
                "A"=> "балаң",
                "І"=> "балаңның",
                "Б"=> "балаңа",
                "Т"=> "балаңды",
                "Ш"=> "балаңнан",
                "Ж"=> "балаңда",
                "К"=> "балаңмен"
            ],
            "сіздің"=> [
                "A"=> "балаңыз",
                "І"=> "балаңыздың",
                "Б"=> "балаңызға",
                "Т"=> "балаңызды",
                "Ш"=> "балаңыздан",
                "Ж"=> "балаңызда",
                "К"=> "балаңызбен"
            ],
            "оның"=> [
                "A"=> "баласы",
                "І"=> "баласының",
                "Б"=> "баласына",
                "Т"=> "баласын",
                "Ш"=> "баласынан",
                "Ж"=> "баласында",
                "К"=> "баласымен"
            ],
            "біздің"=> [
                "A"=> "баламыз",
                "І"=> "баламыздың",
                "Б"=> "баламызға",
                "Т"=> "баламызды",
                "Ш"=> "баламыздан",
                "Ж"=> "баламызда",
                "К"=> "баламызбен"
            ],
            "сендердің"=> [
                "A"=> "балаларың",
                "І"=> "балаларыңның",
                "Б"=> "балаларыңа",
                "Т"=> "балаларыңды",
                "Ш"=> "балаларыңнан",
                "Ж"=> "балаларыңда",
                "К"=> "балаларыңмен"
            ],
            "сіздердің"=> [
                "A"=> "балаларыңыз",
                "І"=> "балаларыңыздың",
                "Б"=> "балаларыңызға",
                "Т"=> "балаларыңызды",
                "Ш"=> "балаларыңыздан",
                "Ж"=> "балаларыңызда",
                "К"=> "балаларыңызбен"
            ],
            "олардың"=> [
                "A"=> "балалары",
                "І"=> "балаларының",
                "Б"=> "балаларына",
                "Т"=> "балаларын",
                "Ш"=> "балаларынан",
                "Ж"=> "балаларында",
                "К"=> "балаларымен"
            ],
            "көпше"=> [
                "A"=> "балалар",
                "І"=> "балалардың",
                "Б"=> "балаларға",
                "Т"=> "балаларды",
                "Ш"=> "балалардан",
                "Ж"=> "балаларда",
                "К"=> "балалармен",
                "менің"=> [
                    "A"=> "балаларым",
                    "І"=> "балаларымның",
                    "Б"=> "балаларыма",
                    "Т"=> "балаларымды",
                    "Ш"=> "балаларымнан",
                    "Ж"=> "балаларымда",
                    "К"=> "балаларыммен"
                ],
                "сенің"=> [
                    "A"=> "балаларың",
                    "І"=> "балаларыңның",
                    "Б"=> "балаларыңа",
                    "Т"=> "балаларыңды",
                    "Ш"=> "балаларыңнан",
                    "Ж"=> "балаларыңда",
                    "К"=> "балаларыңмен"
                ],
                "сіздің"=> [
                    "A"=> "балаларыңыз",
                    "І"=> "балаларыңыздың",
                    "Б"=> "балаларыңызға",
                    "Т"=> "балаларыңызды",
                    "Ш"=> "балаларыңыздан",
                    "Ж"=> "балаларыңызда",
                    "К"=> "балаларыңызбен"
                ],
                "оның"=> [
                    "A"=> "балалары",
                    "І"=> "балаларының",
                    "Б"=> "балаларына",
                    "Т"=> "балаларын",
                    "Ш"=> "балаларынан",
                    "Ж"=> "балаларында",
                    "К"=> "балаларымен"
                ],
                "біздің"=> [
                    "A"=> "балаларымыз",
                    "І"=> "балаларымыздың",
                    "Б"=> "балаларымызға",
                    "Т"=> "балаларымызды",
                    "Ш"=> "балаларымыздан",
                    "Ж"=> "балаларымызда",
                    "К"=> "балаларымызбен"
                ],
                "сендердің"=> [
                    "A"=> "балаларың",
                    "І"=> "балаларыңның",
                    "Б"=> "балаларыңа",
                    "Т"=> "балаларыңды",
                    "Ш"=> "балаларыңнан",
                    "Ж"=> "балаларыңда",
                    "К"=> "балаларыңмен"
                ],
                "сіздердің"=> [
                    "A"=> "балаларыңыз",
                    "І"=> "балаларыңыздың",
                    "Б"=> "балаларыңызға",
                    "Т"=> "балаларыңызды",
                    "Ш"=> "балаларыңыздан",
                    "Ж"=> "балаларыңызда",
                    "К"=> "балаларыңызбен"
                ],
                "олардың"=> [
                    "A"=> "балалары",
                    "І"=> "балаларының",
                    "Б"=> "балаларына",
                    "Т"=> "балаларын",
                    "Ш"=> "балаларынан",
                    "Ж"=> "балаларында",
                    "К"=> "балаларымен"
                ]
            ]
        ];
        
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);
        



    
        $lemma='бала';



        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $declensionResult=$testMorpher->qazaq->Parse($lemma);


        $transaction=reset($container);//get first element of requests history


        //check request parameters, headers, uri
        $request=$transaction['request'];        

        $uri=$request->getUri();
        $this->assertEquals('/qazaq/declension',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('s='.rawurlencode($lemma),$uri->getQuery());


        $this->assertInstanceOf(Qazaq\DeclensionForms::class ,$declensionResult);
        //$this->assertNotNull($declensionResult);
        $this->assertInstanceOf(Qazaq\SameNumberForms::class,$declensionResult->Plural);        


        $this->assertNotNull($declensionResult);
        $this->assertEquals("бала", $declensionResult->Nominative);
        $this->assertEquals("баланың", $declensionResult->Genitive);
        $this->assertEquals("балаға", $declensionResult->Dative);
        $this->assertEquals("баланы", $declensionResult->Accusative);
        $this->assertEquals("баладан", $declensionResult->Ablative);
        $this->assertEquals("балада", $declensionResult->Locative);
        $this->assertEquals("баламен", $declensionResult->Instrumental);

        $this->assertNotNull($declensionResult->FirstPerson);
        $this->assertEquals("балам", $declensionResult->FirstPerson->Nominative);
        $this->assertEquals("баламның", $declensionResult->FirstPerson->Genitive);
        $this->assertEquals("балама", $declensionResult->FirstPerson->Dative);
        $this->assertEquals("баламды", $declensionResult->FirstPerson->Accusative);
        $this->assertEquals("баламнан", $declensionResult->FirstPerson->Ablative);
        $this->assertEquals("баламда", $declensionResult->FirstPerson->Locative);
        $this->assertEquals("баламмен", $declensionResult->FirstPerson->Instrumental);

        $this->assertNotNull($declensionResult->SecondPerson);
        $this->assertEquals("балаң", $declensionResult->SecondPerson->Nominative);
        $this->assertEquals("балаңның", $declensionResult->SecondPerson->Genitive);
        $this->assertEquals("балаңа", $declensionResult->SecondPerson->Dative);
        $this->assertEquals("балаңды", $declensionResult->SecondPerson->Accusative);
        $this->assertEquals("балаңнан", $declensionResult->SecondPerson->Ablative);
        $this->assertEquals("балаңда", $declensionResult->SecondPerson->Locative);
        $this->assertEquals("балаңмен", $declensionResult->SecondPerson->Instrumental);

        $this->assertNotNull($declensionResult->SecondPersonRespectful);
        $this->assertEquals("балаңыз", $declensionResult->SecondPersonRespectful->Nominative);
        $this->assertEquals("балаңыздың", $declensionResult->SecondPersonRespectful->Genitive);
        $this->assertEquals("балаңызға", $declensionResult->SecondPersonRespectful->Dative);
        $this->assertEquals("балаңызды", $declensionResult->SecondPersonRespectful->Accusative);
        $this->assertEquals("балаңыздан", $declensionResult->SecondPersonRespectful->Ablative);
        $this->assertEquals("балаңызда", $declensionResult->SecondPersonRespectful->Locative);
        $this->assertEquals("балаңызбен", $declensionResult->SecondPersonRespectful->Instrumental);

        $this->assertNotNull($declensionResult->ThirdPerson);
        $this->assertEquals("баласы", $declensionResult->ThirdPerson->Nominative);
        $this->assertEquals("баласының", $declensionResult->ThirdPerson->Genitive);
        $this->assertEquals("баласына", $declensionResult->ThirdPerson->Dative);
        $this->assertEquals("баласын", $declensionResult->ThirdPerson->Accusative);
        $this->assertEquals("баласынан", $declensionResult->ThirdPerson->Ablative);
        $this->assertEquals("баласында", $declensionResult->ThirdPerson->Locative);
        $this->assertEquals("баласымен", $declensionResult->ThirdPerson->Instrumental);

        $this->assertNotNull($declensionResult->FirstPersonPlural);
        $this->assertEquals("баламыз", $declensionResult->FirstPersonPlural->Nominative);
        $this->assertEquals("баламыздың", $declensionResult->FirstPersonPlural->Genitive);
        $this->assertEquals("баламызға", $declensionResult->FirstPersonPlural->Dative);
        $this->assertEquals("баламызды", $declensionResult->FirstPersonPlural->Accusative);
        $this->assertEquals("баламыздан", $declensionResult->FirstPersonPlural->Ablative);
        $this->assertEquals("баламызда", $declensionResult->FirstPersonPlural->Locative);
        $this->assertEquals("баламызбен", $declensionResult->FirstPersonPlural->Instrumental);

        $this->assertNotNull($declensionResult->SecondPerson);
        $this->assertEquals("балаң", $declensionResult->SecondPerson->Nominative);
        $this->assertEquals("балаңның", $declensionResult->SecondPerson->Genitive);
        $this->assertEquals("балаңа", $declensionResult->SecondPerson->Dative);
        $this->assertEquals("балаңды", $declensionResult->SecondPerson->Accusative);
        $this->assertEquals("балаңнан", $declensionResult->SecondPerson->Ablative);
        $this->assertEquals("балаңда", $declensionResult->SecondPerson->Locative);
        $this->assertEquals("балаңмен", $declensionResult->SecondPerson->Instrumental);

        $this->assertNotNull($declensionResult->SecondPersonRespectful);
        $this->assertEquals("балаңыз", $declensionResult->SecondPersonRespectful->Nominative);
        $this->assertEquals("балаңыздың", $declensionResult->SecondPersonRespectful->Genitive);
        $this->assertEquals("балаңызға", $declensionResult->SecondPersonRespectful->Dative);
        $this->assertEquals("балаңызды", $declensionResult->SecondPersonRespectful->Accusative);
        $this->assertEquals("балаңыздан", $declensionResult->SecondPersonRespectful->Ablative);
        $this->assertEquals("балаңызда", $declensionResult->SecondPersonRespectful->Locative);
        $this->assertEquals("балаңызбен", $declensionResult->SecondPersonRespectful->Instrumental);

        $this->assertNotNull($declensionResult->ThirdPersonPlural);
        $this->assertEquals("балалары", $declensionResult->ThirdPersonPlural->Nominative);
        $this->assertEquals("балаларының", $declensionResult->ThirdPersonPlural->Genitive);
        $this->assertEquals("балаларына", $declensionResult->ThirdPersonPlural->Dative);
        $this->assertEquals("балаларын", $declensionResult->ThirdPersonPlural->Accusative);
        $this->assertEquals("балаларынан", $declensionResult->ThirdPersonPlural->Ablative);
        $this->assertEquals("балаларында", $declensionResult->ThirdPersonPlural->Locative);
        $this->assertEquals("балаларымен", $declensionResult->ThirdPersonPlural->Instrumental);

        $this->assertNotNull($declensionResult->Plural);
        $this->assertEquals("балалар", $declensionResult->Plural->Nominative);
        $this->assertEquals("балалардың", $declensionResult->Plural->Genitive);
        $this->assertEquals("балаларға", $declensionResult->Plural->Dative);
        $this->assertEquals("балаларды", $declensionResult->Plural->Accusative);
        $this->assertEquals("балалардан", $declensionResult->Plural->Ablative);
        $this->assertEquals("балаларда", $declensionResult->Plural->Locative);
        $this->assertEquals("балалармен", $declensionResult->Plural->Instrumental);

        $this->assertNotNull($declensionResult->Plural->FirstPerson);
        $this->assertEquals("балаларым", $declensionResult->Plural->FirstPerson->Nominative);
        $this->assertEquals("балаларымның", $declensionResult->Plural->FirstPerson->Genitive);
        $this->assertEquals("балаларыма", $declensionResult->Plural->FirstPerson->Dative);
        $this->assertEquals("балаларымды", $declensionResult->Plural->FirstPerson->Accusative);
        $this->assertEquals("балаларымнан", $declensionResult->Plural->FirstPerson->Ablative);
        $this->assertEquals("балаларымда", $declensionResult->Plural->FirstPerson->Locative);
        $this->assertEquals("балаларыммен", $declensionResult->Plural->FirstPerson->Instrumental);

        $this->assertNotNull($declensionResult->Plural->SecondPerson);
        $this->assertEquals("балаларың", $declensionResult->Plural->SecondPerson->Nominative);
        $this->assertEquals("балаларыңның", $declensionResult->Plural->SecondPerson->Genitive);
        $this->assertEquals("балаларыңа", $declensionResult->Plural->SecondPerson->Dative);
        $this->assertEquals("балаларыңды", $declensionResult->Plural->SecondPerson->Accusative);
        $this->assertEquals("балаларыңнан", $declensionResult->Plural->SecondPerson->Ablative);
        $this->assertEquals("балаларыңда", $declensionResult->Plural->SecondPerson->Locative);
        $this->assertEquals("балаларыңмен", $declensionResult->Plural->SecondPerson->Instrumental);

        $this->assertNotNull($declensionResult->Plural->SecondPersonRespectful);
        $this->assertEquals("балаларыңыз", $declensionResult->Plural->SecondPersonRespectful->Nominative);
        $this->assertEquals("балаларыңыздың", $declensionResult->Plural->SecondPersonRespectful->Genitive);
        $this->assertEquals("балаларыңызға", $declensionResult->Plural->SecondPersonRespectful->Dative);
        $this->assertEquals("балаларыңызды", $declensionResult->Plural->SecondPersonRespectful->Accusative);
        $this->assertEquals("балаларыңыздан", $declensionResult->Plural->SecondPersonRespectful->Ablative);
        $this->assertEquals("балаларыңызда", $declensionResult->Plural->SecondPersonRespectful->Locative);
        $this->assertEquals("балаларыңызбен", $declensionResult->Plural->SecondPersonRespectful->Instrumental);

        $this->assertNotNull($declensionResult->Plural->ThirdPerson);
        $this->assertEquals("балалары", $declensionResult->Plural->ThirdPerson->Nominative);
        $this->assertEquals("балаларының", $declensionResult->Plural->ThirdPerson->Genitive);
        $this->assertEquals("балаларына", $declensionResult->Plural->ThirdPerson->Dative);
        $this->assertEquals("балаларын", $declensionResult->Plural->ThirdPerson->Accusative);
        $this->assertEquals("балаларынан", $declensionResult->Plural->ThirdPerson->Ablative);
        $this->assertEquals("балаларында", $declensionResult->Plural->ThirdPerson->Locative);
        $this->assertEquals("балаларымен", $declensionResult->Plural->ThirdPerson->Instrumental);

        $this->assertNotNull($declensionResult->Plural->FirstPersonPlural);
        $this->assertEquals("балаларымыз", $declensionResult->Plural->FirstPersonPlural->Nominative);
        $this->assertEquals("балаларымыздың", $declensionResult->Plural->FirstPersonPlural->Genitive);
        $this->assertEquals("балаларымызға", $declensionResult->Plural->FirstPersonPlural->Dative);
        $this->assertEquals("балаларымызды", $declensionResult->Plural->FirstPersonPlural->Accusative);
        $this->assertEquals("балаларымыздан", $declensionResult->Plural->FirstPersonPlural->Ablative);
        $this->assertEquals("балаларымызда", $declensionResult->Plural->FirstPersonPlural->Locative);
        $this->assertEquals("балаларымызбен", $declensionResult->Plural->FirstPersonPlural->Instrumental);

        $this->assertNotNull($declensionResult->Plural->SecondPerson);
        $this->assertEquals("балаларың", $declensionResult->Plural->SecondPerson->Nominative);
        $this->assertEquals("балаларыңның", $declensionResult->Plural->SecondPerson->Genitive);
        $this->assertEquals("балаларыңа", $declensionResult->Plural->SecondPerson->Dative);
        $this->assertEquals("балаларыңды", $declensionResult->Plural->SecondPerson->Accusative);
        $this->assertEquals("балаларыңнан", $declensionResult->Plural->SecondPerson->Ablative);
        $this->assertEquals("балаларыңда", $declensionResult->Plural->SecondPerson->Locative);
        $this->assertEquals("балаларыңмен", $declensionResult->Plural->SecondPerson->Instrumental);

        $this->assertNotNull($declensionResult->Plural->SecondPersonRespectful);
        $this->assertEquals("балаларыңыз", $declensionResult->Plural->SecondPersonRespectful->Nominative);
        $this->assertEquals("балаларыңыздың", $declensionResult->Plural->SecondPersonRespectful->Genitive);
        $this->assertEquals("балаларыңызға", $declensionResult->Plural->SecondPersonRespectful->Dative);
        $this->assertEquals("балаларыңызды", $declensionResult->Plural->SecondPersonRespectful->Accusative);
        $this->assertEquals("балаларыңыздан", $declensionResult->Plural->SecondPersonRespectful->Ablative);
        $this->assertEquals("балаларыңызда", $declensionResult->Plural->SecondPersonRespectful->Locative);
        $this->assertEquals("балаларыңызбен", $declensionResult->Plural->SecondPersonRespectful->Instrumental);

        $this->assertNotNull($declensionResult->Plural->ThirdPersonPlural);
        $this->assertEquals("балалары", $declensionResult->Plural->ThirdPersonPlural->Nominative);
        $this->assertEquals("балаларының", $declensionResult->Plural->ThirdPersonPlural->Genitive);
        $this->assertEquals("балаларына", $declensionResult->Plural->ThirdPersonPlural->Dative);
        $this->assertEquals("балаларын", $declensionResult->Plural->ThirdPersonPlural->Accusative);
        $this->assertEquals("балаларынан", $declensionResult->Plural->ThirdPersonPlural->Ablative);
        $this->assertEquals("балаларында", $declensionResult->Plural->ThirdPersonPlural->Locative);
        $this->assertEquals("балаларымен", $declensionResult->Plural->ThirdPersonPlural->Instrumental);
    }    

    public function testParse_ExceptionNoWords(): void
    {
        $this->expectException(Qazaq\QazaqWordsNotFound::class);
        //$this->expectExceptionCode(5);
        $this->expectExceptionMessage('Не найдено казахских слов.');

        $parseResults=[        'code'=>5,
        'message'=> 'Не найдено казахских слов.']; //тело сообщения об ошибке содержит информацию
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text,496);
    
        $lemma='test';
    
        $declensionResult=$testMorpher->qazaq->Parse($lemma);

    }


    public function testParse_InvalidServerResponse(): void
    {
        $this->expectException(Morpher\Ws3Client\InvalidServerResponse::class);

        $parseResults=[]; //если пустое тело сообщения об ошибке
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text,496);
    
        $lemma='test';
    
        $declensionResult=$testMorpher->qazaq->Parse($lemma);

    }

    public function testParse_ExceptionNoS(): void
    {
        $this->expectException(Morpher\Ws3Client\EmptyString::class);
        //$this->expectExceptionCode(6);
        $this->expectExceptionMessage('Передана пустая строка.');

        $parseResults=[        'code'=>6,
        'message'=> 'Не указан обязательный параметр: s.']; //тело сообщения об ошибке содержит информацию
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text,400);
    
        $lemma='+++';
    
        $declensionResult=$testMorpher->qazaq->Parse($lemma);

    }

    public function testParse_NetworkError1(): void
    {
        $this->expectException(\GuzzleHttp\Exception\ConnectException::class);
        $testMorpher=MorpherTestHelper::createMockMorpherWithException(new \GuzzleHttp\Exception\ConnectException('connection cannot be established', new \GuzzleHttp\Psr7\Request('GET', 'test')));
        $declensionResult=$testMorpher->qazaq->Parse('двадцать');
    }

    public function testParse_NetworkError2(): void
    {
        $this->expectException(\GuzzleHttp\Exception\RequestException::class);
        $testMorpher=MorpherTestHelper::createMockMorpherWithException(new \GuzzleHttp\Exception\RequestException('connection cannot be established', new \GuzzleHttp\Psr7\Request('GET', 'test')));
        $declensionResult=$testMorpher->qazaq->Parse('двадцать');
    }

}
