<?php declare(strict_types=1);
require_once __DIR__."/../../vendor/autoload.php";

require_once __DIR__."/IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;



final class QueriesLeftTest extends IntegrationBase
{
    
    public  function testQueriesLeft(): void
    {

        $c=self::$testMorpher->QueriesLeftForToday();
        print($c);
        $this->assertTrue($c>0);
    }
}