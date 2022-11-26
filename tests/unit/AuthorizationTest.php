<?php

declare(strict_types=1);

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/MorpherTestHelper.php";

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Morpher\Ws3Client\Exceptions\InvalidServerResponse;
use Morpher\Ws3Client\Exceptions\IpBlocked;
use PHPUnit\Framework\TestCase;
use Morpher\Ws3Client\Russian;
use Morpher\Ws3Client\Ukrainian;
use Psr\Http\Message\RequestInterface;

class AuthorizationTest extends TestCase
{
	public function CallbacksProvider(): array
	{
		return [  //список функций для прогонки через тесты [текст ответа (json), функция вызова запроса]
		          [
			          'GET',
			          '{}',
			          function($testMorpher) {
				          $testMorpher->russian->parse('тест');
			          }
		          ],//dataset #0
		          [
			          'GET',
			          '{}',
			          function($testMorpher) {
				          $testMorpher->qazaq->parse('тест');
			          }
		          ],//dataset #1
		          [
			          'GET',
			          '{"n":[],"unit":[]}',
			          function($testMorpher) {
				          $testMorpher->russian->spell(10, 'тест');
			          }
		          ],//dataset #2
		          [
			          'GET',
			          '{}',
			          function($testMorpher) {
				          $testMorpher->russian->spellDate('1988-07-01');
			          }
		          ],//dataset #3
		          [
			          'GET',
			          '{"n":[],"unit":[]}',
			          function($testMorpher) {
				          $testMorpher->russian->spellOrdinal(10, 'тест');
			          }
		          ],//dataset #4
		          [
			          'GET',
			          '{}',
			          function($testMorpher) {
				          $testMorpher->russian->adjectiveGenders("уважаемый");
			          }
		          ],//dataset #5
		          [
			          'GET',
			          '[]',
			          function($testMorpher) {
				          $testMorpher->russian->adjectivize("мытыщи");
			          }
		          ],//dataset #6
		          [
			          'POST',
			          '"тест"',
			          function($testMorpher) {
				          $testMorpher->russian->addStressmarks("тест");
			          }
		          ],//dataset #7
		          [
			          'GET',
			          '[]',
			          function($testMorpher) {
				          $testMorpher->russian->userDict->getAll();
			          }
		          ],//dataset #8
		          [
			          'POST',
			          '[]',
			          function($testMorpher) {
				          $testMorpher->russian->userDict->addOrUpdate(new Russian\CorrectionEntry([
					          'singular' => [
						          'И' => 'чебуратор',
						          'Р' => 'чебурыла'
					          ]
				          ]));
			          }
		          ],//dataset #9
		          [
			          'DELETE',
			          '[]',
			          function($testMorpher) {
				          $testMorpher->russian->userDict->remove('чебуратор');
			          }
		          ],//dataset #10
		          [
			          'GET',
			          '[]',
			          function($testMorpher) {
				          $testMorpher->ukrainian->parse('тест');
			          }
		          ],//dataset #11
		          [
			          'GET',
			          '[]',
			          function($testMorpher) {
				          $testMorpher->ukrainian->userDict->getAll();
			          }
		          ],//dataset #12
		          [
			          'POST',
			          '[]',
			          function($testMorpher) {
				          $testMorpher->ukrainian->userDict->addOrUpdate(new Ukrainian\CorrectionEntry([
					          'singular' => [
						          'Н' => 'чебуратор',
						          'Р' => 'чебурыла'
					          ]
				          ]));
			          }
		          ],//dataset #13
		          [
			          'DELETE',
			          '[]',
			          function($testMorpher) {
				          $testMorpher->ukrainian->userDict->remove('чебуратор');
			          }
		          ],//dataset #14
		          [
			          'GET',
			          '111',
			          function($testMorpher) {
				          $testMorpher->getQueriesLeftForToday();
			          }
		          ],//dataset #15

		];
	}

	/**
	 * @dataProvider  CallbacksProvider
	 */
	public function testAuthorization(string $method, string $requestResult, callable $callback): void
	{
		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $requestResult);

		$callback($testMorpher);

		$this->assertAuthorization(reset($container)['request'], $method);
	}

	public function assertAuthorization(RequestInterface $request, string $method = 'GET'): void
	{
		$this->assertEquals($method, $request->getMethod());
		$this->assertTrue($request->hasHeader('Accept'));
		$this->assertEquals(["application/json"], $request->getHeaders()['Accept']);
		$this->assertTrue($request->hasHeader('Authorization'));
		$this->assertEquals(["Basic " . base64_encode('testtoken')], $request->getHeaders()['Authorization']);
	}

	/**
	 * @dataProvider  CallbacksProvider
	 */
	public function testNoToken(string $method, string $requestResult, callable $callback): void
	{
		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $requestResult, 200, '');

		$callback($testMorpher);

		$this->assertAuthorizationNoToken(reset($container)['request'], $method);
	}

	public function assertAuthorizationNoToken(RequestInterface $request,
		string $method = 'GET'): void
	{
		$this->assertEquals($method, $request->getMethod());
		$this->assertTrue($request->hasHeader('Accept'));
		$this->assertEquals(["application/json"], $request->getHeaders()['Accept']);
		$this->assertFalse($request->hasHeader('Authorization'));
	}

	/**
	 * @dataProvider  CallbacksProvider
	 */
	public function testServerError500(string $method, string $requestResult, callable $callback): void
	{
		$this->expectException(ServerException::class);
		$this->expectExceptionMessage('Error 500');

		$testMorpher = MorpherTestHelper::createMockMorpherWithException(
			new ServerException('Error 500',
			new Request($method, 'test'), new Response(500, [], ''))
		);

		$callback($testMorpher);
	}

	/**
	 * @dataProvider  CallbacksProvider
	 */
	public function testIpBlocked(string $method, string $requestResult, callable $callback): void
	{
		$this->expectException(IpBlocked::class);
		$this->expectExceptionMessage('IP заблокирован.');

		$parseResults = [
			'code' => 3,
			'message' => 'IP заблокирован.'
		];
		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);
		$container = [];
		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text, 444);

		$callback($testMorpher);
	}

	/**
	 * @dataProvider  CallbacksProvider
	 */
	public function testParse_NetworkError1(string $method, string $requestResult, callable $callback): void
	{
		$this->expectException(ConnectException::class);
		$testMorpher = MorpherTestHelper::createMockMorpherWithException(
			new ConnectException('connection cannot be established',
			new Request($method, 'test'))
		);
		$callback($testMorpher);
	}

	/**
	 * @dataProvider  CallbacksProvider
	 */
	public function testParse_NetworkError2(string $method, string $requestResult, callable $callback): void
	{
		$this->expectException(RequestException::class);
		$testMorpher = MorpherTestHelper::createMockMorpherWithException(
			new RequestException('connection cannot be established',
			new Request($method, 'test'))
		);
		$callback($testMorpher);
	}

	/**
	 * @dataProvider  CallbacksProvider
	 */
	public function testParse_UnknownError(string $method, string $requestResult, callable $callback): void
	{
		$this->expectException(InvalidServerResponse::class);
		$this->expectExceptionMessage('Неизвестный код ошибки');

		$parseResults = [
			'code' => 100,
			'message' => 'Непонятная ошибка.'
		];
		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text, 444);

		$callback($testMorpher);
	}

	/**
	 * @dataProvider  CallbacksProvider
	 */
	public function testParse_InvalidServerResponse(string $method, string $requestResult, callable $callback): void
	{
		$this->expectException(InvalidServerResponse::class);

		$parseResults = []; //если пустое тело сообщения об ошибке
		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text, 496);

		$callback($testMorpher);
	}
}