<?php declare(strict_types=1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;



final class RussianAddStressmarksTest extends IntegrationBase
{
    public function testStressmarks_Success(): void
    {
        $result=self::$testMorpher->russian->AddStressmarks('Балет Петра Чайковского "Щелкунчик"');


        $this->assertEquals('Бале́т Петра́ Чайко́вского "Щелку́нчик"',$result);

        

    }

    public function testStressmarks_Empty(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        
        self::$testMorpher->russian->AddStressmarks("   ");
    }


}