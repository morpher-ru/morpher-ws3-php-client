<?php declare(strict_types=1);
require_once __DIR__."/../../vendor/autoload.php";

require_once __DIR__."/MorpherTestHelper.php";

use Morpher\Ws3Client\InvalidArgumentEmptyString;
use PHPUnit\Framework\TestCase;




final class QueriesLeftTest extends TestCase
{
    
    public  function testQueriesLeft(): void
    {
        $parseResults=111;
        $return_text=json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher=MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $result=$testMorpher->QueriesLeftForToday();


        $transaction=reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request=$transaction['request'];       
        $this->assertEquals('GET', $request->getMethod());

        $this->assertTrue($request->hasHeader('Accept'));
        $this->assertEquals(["application/json"], $request->getHeaders()['Accept']);

        $uri=$request->getUri();
        $this->assertEquals('/get_queries_left_for_today',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('',$uri->getQuery());

        $this->assertIsInt($result);
        $this->assertEquals(111,$result);


    }
}