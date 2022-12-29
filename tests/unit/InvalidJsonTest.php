<?php declare(strict_types = 1);

require_once __DIR__."/../../vendor/autoload.php";

use Morpher\Ws3Client\InvalidServerResponse;
use PHPUnit\Framework\TestCase;
use Morpher\Ws3Client\Russian as Russian;
use Morpher\Ws3Client\Ukrainian as Ukrainian;


final class InvalidJsonTest extends TestCase
{
    public function CallbacksProvider(): array
    {
        return [  // список функций для прогона через тесты
            [function ($m) {$m->russian->parse('тест');     }],//dataset #0
            [function ($m) {$m->qazaq->parse('тест');       }],//dataset #1
            [function ($m) {$m->russian->spell(10,'тест');  }],//dataset #2
            [function ($m) {$m->russian->spellDate('1988-07-01');  }],//dataset #3
            [function ($m) {$m->russian->spellOrdinal(7518, 'колесо');  }],//dataset #4
            [function ($m) {$m->russian->getAdjectiveGenders("уважаемый");  }],//dataset #5
            [function ($m) {$m->russian->adjectivize("мытыщи");  }],//dataset #6
            [function ($m) {$m->russian->addStressMarks("тест"); }],//dataset #7
            [function ($m) {$m->russian->userDict->getAll();     }],//dataset #8
            [function ($m) {$m->russian->userDict->addOrUpdate(new Russian\CorrectionEntry(['singular' => ['И' => 'чебуратор','Р' => 'чебурыла']]));     }],//dataset #9
            [function ($m) {$m->russian->userDict->remove('чебуратор'); }],//dataset #10
            [function ($m) {$m->ukrainian->parse('тест');     }],//dataset #11
            [function ($m) {$m->ukrainian->userDict->getAll();     }],//dataset #12
            [function ($m) {$m->ukrainian->userDict->addOrUpdate(new Ukrainian\CorrectionEntry(['singular' => ['Н' => 'чебуратор','Р' => 'чебурыла']]));     }],//dataset #13
            [function ($m) {$m->ukrainian->userDict->remove('чебуратор');     }],//dataset #14
            [function ($m) {$m->getQueriesLeftForToday();     }],//dataset #15
        ];
    }

    /**
     * @dataProvider  CallbacksProvider
     */    
    public function testInvalidJsonResponse(callable $callback): void
    {
        $this->expectException(InvalidServerResponse::class);

        $return_text = '{"И":"тест","Р":"тесте",-}';
        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);
        $callback($testMorpher);
    }
}
