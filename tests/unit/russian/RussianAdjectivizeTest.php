<?php declare(strict_types = 1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../MorpherTestHelper.php";

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\Russian as Russian;



final class RussianAdjectivizeTest extends TestCase
{

    public function testAdjectivize_Success(): void
    {
        $parseResults = [
            "мытыщинский",
            "мытыщенский"
        ];

        $return_text = json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $name = "мытыщи";

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $list = $testMorpher->russian->adjectivize($name);

        $transaction = reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request = $transaction['request'];        

        $uri = $request->getUri();
        $this->assertEquals('/russian/adjectivize',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('s='.rawurlencode($name),$uri->getQuery());

        $this->assertNotNull($list);
        $this->assertIsArray($list);
        $this->assertCount(2,$list);

        $this->assertContains("мытыщинский",$list);
        $this->assertContains("мытыщенский", $list);    
    }

    public function testAdjectivize_Empty(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);

        $container = [];

        $responseJson = '{"code": 6, "message": "empty"}';

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $responseJson, 400);
    
        $declensionResult = $testMorpher->russian->adjectivize('   ');
    }
}
