<?php declare(strict_types=1);

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../src/Morpher.php";
require_once __DIR__."/../src/WebClientBase.php";

use PHPUnit\Framework\TestCase;

//use Morpher\Ws3Client\Morpher;
use Morpher\Ws3Client\Qazaq as Qazaq;



final class DeclensionQazaqTest extends TestCase
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

    
        $WebClientMock=Mockery::mock(Morpher\Ws3Client\WebClientBase::class);
        $WebClientMock->shouldReceive('get_request')->andReturn($return_text);
    
        $testMorpher=new Morpher\Ws3Client\Morpher($WebClientMock);
    
        $lemma='тест';
    
        $declensionResult=$testMorpher->qazaq->Parse($lemma);



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
/* 
function test_temp()
{
    $WebClientMock=new TestWebClient();

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
    //print_r($return_text);die();

    $WebClientMock->setup($return_text);

    $testMorpher=new Morpher\Ws3Client\Morpher($WebClientMock);

    $lemma='тест';

    $declensionResult=$testMorpher->russian->Parse($lemma);
    //$this->assertEquals()
    print_r($declensionResult);

}

test_temp(); */