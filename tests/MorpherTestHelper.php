<?php
require_once __DIR__."/../src/Morpher.php";
require_once __DIR__."/../src/WebClient.php";

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;

use Morpher\Ws3Client\WebClient;

class MorpherTestHelper
{
    public static function createMockMorpher(Array &$container,string $return_text='',int $code=200): Morpher\Ws3Client\Morpher
    {
         $mock = new MockHandler([
             new Response($code, [], $return_text)
         ]);

        
        $handlerStack = HandlerStack::create($mock);
        

        $history = Middleware::history($container);
        // Add the history middleware to the handler stack.
        $handlerStack->push($history);
                
        $webClientMock=new WebClient('https://test.uu','testtoken',10,$handlerStack);
  
        $testMorpher=new Morpher\Ws3Client\Morpher($webClientMock);

        return $testMorpher;
    }    
    
}