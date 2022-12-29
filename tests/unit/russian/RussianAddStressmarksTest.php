<?php declare(strict_types = 1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../MorpherTestHelper.php";

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\Russian as Russian;



final class RussianAddStressmarksTest extends TestCase
{

    public function testAddStressmarks_Success(): void
    {
        $parseResults = 'Бале́т Петра́ Чайко́вского "Щелку́нчик"';

        $return_text = json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $text = 'Балет Петра Чайковского "Щелкунчик"';

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $result = $testMorpher->russian->addStressMarks($text);

        $transaction = reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request = $transaction['request'];       
        $this->assertEquals('POST', $request->getMethod());

        $this->assertTrue($request->hasHeader('Accept'));
        $this->assertEquals(["application/json"], $request->getHeaders()['Accept']);

        $this->assertTrue($request->hasHeader('Content-Type'));
        $this->assertEquals(["text/plain; charset=utf-8"], $request->getHeaders()['Content-Type']);

        $uri = $request->getUri();
        $this->assertEquals('/russian/addstressmarks',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('',$uri->getQuery());
        $this->assertEquals('Балет Петра Чайковского "Щелкунчик"',(string)($request->getBody()));

        $this->assertEquals('Бале́т Петра́ Чайко́вского "Щелку́нчик"',$result);
    }
}
