<?php declare(strict_types = 1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;
use Morpher\Ws3Client\Russian\CorrectionForms;
use Morpher\Ws3Client\Russian\CorrectionEntry;
use Morpher\Ws3Client\Russian\UserDict;


final class RussianUserDictTokenNotFoundTest extends TestCase
{
    static Morpher $testMorpher;

    public static function setUpBeforeClass(): void
    {
        $token = '41e2111a-767b-4a07-79A3-d52c02cb5a0d';

        self::$testMorpher = new Morpher(IntegrationBase::BASE_URL,$token);
    }

    public function testUserDict_NoTokenError_AddOrUpdate(): void
    {
        $this->expectException(\Morpher\Ws3Client\TokenNotFound::class);
        $word = 'чебуратор';
        $correction = new CorrectionEntry();
        $correction->Singular->Nominative = $word;
        $correction->Singular->Locative = 'в чебураторке';
        $correction->Plural->Locative = 'в чебураториях';

        self::$testMorpher->russian->userDict->addOrUpdate($correction);
    }

    public function testUserDict_NoTokenError_GetAll(): void
    {
        $this->expectException(\Morpher\Ws3Client\TokenNotFound::class);
        self::$testMorpher->russian->userDict->getAll();
    }

    public function testUserDict_NoTokenError_Remove(): void
    {
        $this->expectException(\Morpher\Ws3Client\TokenNotFound::class);
        $word = 'чебуратор';
        self::$testMorpher->russian->userDict->remove($word);
    }
}
