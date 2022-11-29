<?php declare(strict_types=1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;
use Morpher\Ws3Client\Russian\CorrectionForms;
use Morpher\Ws3Client\Russian\CorrectionEntry;
use Morpher\Ws3Client\Russian\UserDict;

final class RussianUserDictIncorrectTokenTest extends TestCase
{
    static Morpher $testMorpher;

    public static function setUpBeforeClass(): void
    {
        $token='35523523523523521f4f432f434f';
        self::$testMorpher=new Morpher(IntegrationBase::BASE_URL,$token);
    }

    public function testUserDict_NoTokenError_AddOrUpdate(): void
    {
        $this->expectException(\Morpher\Ws3Client\TokenIncorrectFormat::class);
        $word='чебуратор';
        $correction=new CorrectionEntry();
        $correction->Singular->Nominative=$word;
        $correction->Singular->Locative='в чебураторке';
        $correction->Plural->Locative='в чебураториях';

        self::$testMorpher->russian->userDict->AddOrUpdate($correction);
    }

    public function testUserDict_NoTokenError_GetAll(): void
    {
        $this->expectException(\Morpher\Ws3Client\TokenIncorrectFormat::class);
        self::$testMorpher->russian->userDict->GetAll();
    }

    public function testUserDict_NoTokenError_Remove(): void
    {
        $this->expectException(\Morpher\Ws3Client\TokenIncorrectFormat::class);
        $word='чебуратор';
        self::$testMorpher->russian->userDict->Remove($word);
    }
}
