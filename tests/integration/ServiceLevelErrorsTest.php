<?php declare(strict_types=1);
require_once __DIR__."/../../vendor/autoload.php";

require_once __DIR__."/IntegrationBase.php";

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;
use Morpher\Ws3Client\Ukrainian as Ukrainian;

final class ServiceLevelErrorsTest extends TestCase
{

    public function CallbacksProvider():array
    {
        return [  //список функций для прогонки через тесты
            [function ($testMorpher)    {     $testMorpher->russian->Parse('тест');     }],//dataset #0
            [function ($testMorpher)    {     $testMorpher->qazaq->Parse('тест');       }],//dataset #1
            [function ($testMorpher)    {     $testMorpher->russian->Spell(10,'тест');  }],//dataset #2
            [function ($testMorpher)    {     $testMorpher->russian->SpellDate('1988-07-01');  }],//dataset #3
            [function ($testMorpher)    {     $testMorpher->russian->SpellOrdinal(7518 ,'колесо');  }],//dataset #4
            [function ($testMorpher)    {     $testMorpher->russian->AdjectiveGenders("уважаемый");  }],//dataset #5
            [function ($testMorpher)    {     $testMorpher->russian->Adjectivize("мытыщи");  }],//dataset #6
            [function ($testMorpher)    {     $testMorpher->russian->AddStressmarks("тест");  }],//dataset #7
            [function ($testMorpher)    {     $testMorpher->russian->userDict->GetAll();     }],//dataset #8
            [function ($testMorpher)    {     $testMorpher->russian->userDict->AddOrUpdate(new Russian\CorrectionEntry(['singular'=>['И'=>'чебуратор','Р'=>'чебурыла']]));     }],//dataset #9
            [function ($testMorpher)    {     $testMorpher->russian->userDict->Remove('чебуратор');     }],//dataset #10
            [function ($testMorpher)    {     $testMorpher->ukrainian->Parse('тест');     }],//dataset #11     
            [function ($testMorpher)    {     $testMorpher->ukrainian->userDict->GetAll();     }],//dataset #12
            [function ($testMorpher)    {     $testMorpher->ukrainian->userDict->AddOrUpdate(new Ukrainian\CorrectionEntry(['singular'=>['Н'=>'чебуратор','Р'=>'чебурыла']]));     }],//dataset #13
            [function ($testMorpher)    {     $testMorpher->ukrainian->userDict->Remove('чебуратор');     }],//dataset #14
            //[function ($testMorpher)    {     $testMorpher->getQueriesLeftForToday();     }],//don't pass, "token not found" not raises
        
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