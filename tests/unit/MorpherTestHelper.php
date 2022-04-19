<?php
require_once __DIR__."/../../vendor/autoload.php";


use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;

use Morpher\Ws3Client\WebClient;

class MorpherTestHelper
{
    public static function createMockMorpher(Array &$container,string $return_text='',int $code=200,$token='testtoken'): \Morpher\Ws3Client\Morpher
    {
         $mock = new MockHandler([
             new Response($code, [], $return_text)
         ]);

        
        $handlerStack = HandlerStack::create($mock);
        

        $history = Middleware::history($container);
        // Add the history middleware to the handler stack.
        $handlerStack->push($history);
                
        $testMorpher=new \Morpher\Ws3Client\Morpher('https://test.uu',$token,10,$handlerStack);

        return $testMorpher;
    }    

    
    public static function createMockMorpherWithException(\GuzzleHttp\Exception\TransferException $exception): \Morpher\Ws3Client\Morpher
    {
         $mock = new MockHandler([
             $exception                 //new RequestException('Error Communicating with Server', new Request('GET', 'test'))
         ]);

        
        $handlerStack = HandlerStack::create($mock);      
                
        $testMorpher=new \Morpher\Ws3Client\Morpher('https://test.uu','testtoken',10,$handlerStack);

        return $testMorpher;
    }    

}