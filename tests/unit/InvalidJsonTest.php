<?php

declare(strict_types=1);

require_once __DIR__ . "/../../vendor/autoload.php";

use Morpher\Ws3Client\Exceptions\InvalidServerResponse;
use PHPUnit\Framework\TestCase;

final class InvalidJsonTest extends TestCase
{
	public function CallbacksProvider(): array
	{
		return [  //список функций для прогонки через тесты
		          [
			          function($testMorpher) {
				          $testMorpher->russian->parse('тест');
			          }
		          ],
		          //dataset #0
		          [
			          function($testMorpher) {
				          $testMorpher->qazaq->parse('тест');
			          }
		          ],
		          //dataset #1
		          [
			          function($testMorpher) {
				          $testMorpher->russian->spell(10, 'тест');
			          }
		          ],
		          //dataset #2
		          [
			          function($testMorpher) {
				          $testMorpher->russian->spellDate('1988-07-01');
			          }
		          ],
		          //dataset #3
		          [
			          function($testMorpher) {
				          $testMorpher->russian->spellOrdinal(7518, 'колесо');
			          }
		          ],
		          //dataset #4
		          [
			          function($testMorpher) {
				          $testMorpher->russian->adjectiveGenders("уважаемый");
			          }
		          ],
		          //dataset #5
		          [
			          function($testMorpher) {
				          $testMorpher->russian->adjectivize("мытыщи");
			          }
		          ],
		          //dataset #6
		          [
			          function($testMorpher) {
				          $testMorpher->russian->addStressMarks("тест");
			          }
		          ],
		          //dataset #7
		          [
			          function($testMorpher) {
				          $testMorpher->russian->userDict->getAll();
			          }
		          ],
		          //dataset #8
		          [
			          function($testMorpher) {
				          $testMorpher->ukrainian->parse('тест');
			          }
		          ],
		          //dataset #11
		          [
			          function($testMorpher) {
				          $testMorpher->ukrainian->userDict->getAll();
			          }
		          ],
		          //dataset #12
		          [
			          function($testMorpher) {
				          $testMorpher->getQueriesLeftForToday();
			          }
		          ],
		          //dataset #15

		];
	}

	/**
	 * @dataProvider  CallbacksProvider
	 */
	public function testInvalidJsonResponse(callable $callback): void
	{
		$token = '23525555555555555555555555555555555555555555555555';// incorrect format token
		$return_text = '{"И":"тест","Р":"тесте",-}';
		try
		{
			$container = [];

			$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);
			$callback($testMorpher);
		}
		catch (InvalidServerResponse $ex)
		{
			$this->assertEquals($ex->response, $return_text);

			return;
		}
		$this->fail("test failed - exception InvalidServerResponse not catched"); //test failed if exception not catched
	}
}