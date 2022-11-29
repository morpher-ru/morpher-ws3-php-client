<?php declare(strict_types=1);
require_once __DIR__."/../../vendor/autoload.php";

require_once __DIR__."/IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;


final class QueriesLeftNoTokenTest extends TestCase
{
    static Morpher $testMorpher;

    public static function setUpBeforeClass(): void
    {
        $token='';

        self::$testMorpher=new Morpher(IntegrationBase::BASE_URL,$token);
    }
    
    public  function testQueriesLeft(): void
    {
        $c=self::$testMorpher->getQueriesLeftForToday();
        print "\r\n";
        print($c." queries left for today\r\n");
        $this->assertTrue($c>0,"FREE daily queries limit exceed, or incorrect response");
    }
}