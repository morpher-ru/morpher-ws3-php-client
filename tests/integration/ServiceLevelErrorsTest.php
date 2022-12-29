<?php declare(strict_types = 1);
require_once __DIR__."/../../vendor/autoload.php";

require_once __DIR__."/IntegrationBase.php";

use Morpher\Ws3Client\ConnectionError;
use Morpher\Ws3Client\TokenIncorrectFormat;
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;
use Morpher\Ws3Client\Ukrainian as Ukrainian;

final class ServiceLevelErrorsTest extends TestCase
{
    public function CallbacksProvider():array
    {
        return [  //список функций для прогонки через тесты
            [function ($testMorpher)    {     $testMorpher->russian->parse('тест');     }],//dataset #0
            [function ($testMorpher)    {     $testMorpher->qazaq->parse('тест');       }],//dataset #1
            [function ($testMorpher)    {     $testMorpher->russian->spell(10,'тест');  }],//dataset #2
            [function ($testMorpher)    {     $testMorpher->russian->spellDate('1988-07-01');  }],//dataset #3
            [function ($testMorpher)    {     $testMorpher->russian->spellOrdinal(7518 ,'колесо');  }],//dataset #4
            [function ($testMorpher)    {     $testMorpher->russian->getAdjectiveGenders("уважаемый");  }],//dataset #5
            [function ($testMorpher)    {     $testMorpher->russian->adjectivize("мытыщи");  }],//dataset #6
            [function ($testMorpher)    {     $testMorpher->russian->addStressMarks("тест");  }],//dataset #7
            [function ($testMorpher)    {     $testMorpher->russian->userDict->getAll();     }],//dataset #8
            [function ($testMorpher)    {     $testMorpher->russian->userDict->addOrUpdate(new Russian\CorrectionEntry(['singular' => ['И' => 'чебуратор','Р' => 'чебурыла']]));     }],//dataset #9
            [function ($testMorpher)    {     $testMorpher->russian->userDict->remove('чебуратор');     }],//dataset #10
            [function ($testMorpher)    {     $testMorpher->ukrainian->parse('тест');     }],//dataset #11
            [function ($testMorpher)    {     $testMorpher->ukrainian->userDict->getAll();     }],//dataset #12
            [function ($testMorpher)    {     $testMorpher->ukrainian->userDict->addOrUpdate(new Ukrainian\CorrectionEntry(['singular' => ['Н' => 'чебуратор','Р' => 'чебурыла']]));     }],//dataset #13
            [function ($testMorpher)    {     $testMorpher->ukrainian->userDict->remove('чебуратор');     }],//dataset #14
            [function ($testMorpher)    {     $testMorpher->getQueriesLeftForToday();     }],//don't pass, "token not found" not raises
        
        ];
    }

    /**
     * @dataProvider  CallbacksProvider
     */    
    public function testTokenIncorrectFormatError(callable $callback): void
    {
        $token = '23525555555555555555555555555555555555555555555555';// incorrect format token

        $testMorpher = new Morpher(IntegrationBase::BASE_URL,$token);     

        $this->expectException(TokenIncorrectFormat::class);

        $callback($testMorpher);
    }

    /**
     * @dataProvider  CallbacksProvider
     */ 
    public function testTokenNotFoundError(callable $callback): void
    {
        $token = '41e2111a-767b-4a07-79A3-d52c02cb5a0d';// not existing token, valid length
 
        $testMorpher = new Morpher(IntegrationBase::BASE_URL, $token);

        $this->expectException(\Morpher\Ws3Client\TokenNotFound::class);

        $callback($testMorpher);
    }

    /**
     * @dataProvider  CallbacksProvider
     */ 
    public function testTimeoutError(callable $callback): void
    {
        // non-existing ip, timeout in 0.1 sec
        $testMorpher = new Morpher('http://10.200.200.200','',0.1);     

        $this->expectException(ConnectionError::class);

        $callback($testMorpher);
    }
}
