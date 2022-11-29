<?php
declare(strict_types=1);
require_once __DIR__ . "/../../../vendor/autoload.php";

require_once __DIR__ . "/../IntegrationBase.php";

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;
use Morpher\Ws3Client\Russian\CorrectionForms;
use Morpher\Ws3Client\Russian\CorrectionEntry;
use Morpher\Ws3Client\Russian\Gender;
use Morpher\Ws3Client\Russian\UserDict;

final class RussianUserDictTest extends IntegrationBase
{
    public function testUserDict_Success(): void
    {
        $word = 'чебуратор';
        self::$testMorpher->russian->userDict->Remove($word);

        $list = self::$testMorpher->russian->userDict->GetAll();

        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $this->assertTrue(false, "Слово снова найдено в словаре после удаления.");
            }
        }

        $correction = new CorrectionEntry();
        $correction->Singular->Nominative = $word;
        $correction->Singular->Locative = 'в чебураторке';
        $correction->Plural->Locative = 'в чебураториях';

        self::$testMorpher->russian->userDict->AddOrUpdate($correction);

        $list = self::$testMorpher->russian->userDict->GetAll();
        $found = false;
        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $found = true;

                $this->assertNull($item->Singular->Genitive);
                $this->assertNull($item->Singular->Dative);
                $this->assertNull($item->Singular->Accusative);
                $this->assertNull($item->Singular->Instrumental);
                $this->assertNull($item->Singular->Prepositional);
                $this->assertEquals('в чебураторке', $item->Singular->Locative,
                    "Не удалось добавление косвенной формы в словаре");

                $this->assertNull($item->Plural->Nominative);

                $this->assertNull($item->Plural->Genitive);
                $this->assertNull($item->Plural->Dative);
                $this->assertNull($item->Plural->Accusative);
                $this->assertNull($item->Plural->Instrumental);
                $this->assertNull($item->Plural->Prepositional);
                $this->assertEquals('в чебураториях', $item->Plural->Locative,
                    "Не удалось добавление косвенной формы в словаре");

            }
        }
        $this->assertTrue($found, "Слово не найдено в словаре после добавления.");

        $correction = new CorrectionEntry();
        $correction->Singular->Nominative = $word;
        $correction->Singular->Locative = 'в чебурелии';
        $correction->Plural->Locative = 'в чебурелиях';

        self::$testMorpher->russian->userDict->AddOrUpdate($correction);

        $list = self::$testMorpher->russian->userDict->GetAll();
        $found = false;
        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $found = true;

                $this->assertNull($item->Singular->Genitive);
                $this->assertNull($item->Singular->Dative);
                $this->assertNull($item->Singular->Accusative);
                $this->assertNull($item->Singular->Instrumental);
                $this->assertNull($item->Singular->Prepositional);
                $this->assertEquals('в чебурелии', $item->Singular->Locative,
                    "Не удалось обновление косвенной формы в словаре");

                $this->assertNull($item->Plural->Nominative);

                $this->assertNull($item->Plural->Genitive);
                $this->assertNull($item->Plural->Dative);
                $this->assertNull($item->Plural->Accusative);
                $this->assertNull($item->Plural->Instrumental);
                $this->assertNull($item->Plural->Prepositional);
                $this->assertEquals('в чебурелиях', $item->Plural->Locative,
                    "Не удалось обновление  косвенной формы в словаре");

            }
        }
        $this->assertTrue($found, "Слово не найдено в словаре после обновления.");

        self::$testMorpher->russian->userDict->Remove($word);

        $list = self::$testMorpher->russian->userDict->GetAll();

        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $this->assertTrue(false, "Слово снова найдено в словаре после удаления.");
            }
        }
    }

    /*
        public function testUserDictGender_Success(): void
        {
            $word='чебуратор';
            self::$testMorpher->russian->userDict->Remove($word);

            $list=self::$testMorpher->russian->userDict->GetAll();

            foreach ($list as $item)
            {
                if ($item->Singular->Nominative == $word)
                {
                    $this->assertTrue(false,"Слово снова найдено в словаре после удаления.");
                }
            }

            $correction=new CorrectionEntry();
            $correction->Singular->Nominative=$word;
            $correction->Singular->Locative='в чебураторке';
            $correction->Plural->Locative='в чебураториях';
            $correction->Gender=Gender::Feminine;

            self::$testMorpher->russian->userDict->AddOrUpdate($correction);

            $list=self::$testMorpher->russian->userDict->GetAll();
            $found=false;
            foreach ($list as $item)
            {
                if ($item->Singular->Nominative == $word)
                {
                    $found=true;

                    $this->assertNull( $item->Singular->Genitive);
                    $this->assertNull($item->Singular->Dative);
                    $this->assertNull($item->Singular->Accusative);
                    $this->assertNull($item->Singular->Instrumental);
                    $this->assertNull($item->Singular->Prepositional);
                    $this->assertEquals('в чебураторке', $item->Singular->Locative,"Не удалось добавление косвенной формы в словаре");

                    $this->assertNull( $item->Plural->Nominative);

                    $this->assertNull( $item->Plural->Genitive);
                    $this->assertNull($item->Plural->Dative);
                    $this->assertNull($item->Plural->Accusative);
                    $this->assertNull($item->Plural->Instrumental);
                    $this->assertNull($item->Plural->Prepositional);
                    $this->assertEquals('в чебураториях', $item->Plural->Locative,"Не удалось добавление косвенной формы в словаре");
                    $this->assertEquals(Gender::Feminine,$item->Gender);
                }
            }
            $this->assertTrue($found,"Слово не найдено в словаре после добавления.");



            $correction=new CorrectionEntry();
            $correction->Singular->Nominative=$word;
            $correction->Singular->Locative='в чебурелии';
            $correction->Plural->Locative='в чебурелиях';
            $correction->Gender=Gender::Masculine;

            self::$testMorpher->russian->userDict->AddOrUpdate($correction);

            $list=self::$testMorpher->russian->userDict->GetAll();
            $found=false;
            foreach ($list as $item)
            {
                if ($item->Singular->Nominative == $word)
                {
                    $found=true;

                    $this->assertNull( $item->Singular->Genitive);
                    $this->assertNull($item->Singular->Dative);
                    $this->assertNull($item->Singular->Accusative);
                    $this->assertNull($item->Singular->Instrumental);
                    $this->assertNull($item->Singular->Prepositional);
                    $this->assertEquals('в чебурелии', $item->Singular->Locative,"Не удалось обновление косвенной формы в словаре");

                    $this->assertNull( $item->Plural->Nominative);

                    $this->assertNull( $item->Plural->Genitive);
                    $this->assertNull($item->Plural->Dative);
                    $this->assertNull($item->Plural->Accusative);
                    $this->assertNull($item->Plural->Instrumental);
                    $this->assertNull($item->Plural->Prepositional);
                    $this->assertEquals('в чебурелиях', $item->Plural->Locative,"Не удалось обновление  косвенной формы в словаре");

                    $this->assertEquals(Gender::Masculine,$item->Gender);
                }
            }
            $this->assertTrue($found,"Слово не найдено в словаре после обновления.");

            self::$testMorpher->russian->userDict->Remove($word);

            $list=self::$testMorpher->russian->userDict->GetAll();

            foreach ($list as $item)
            {
                if ($item->Singular->Nominative == $word)
                {
                    $this->assertTrue(false,"Слово снова найдено в словаре после удаления.");
                }
            }
        }
     */
    public function testUserDict2_Success(): void
    {
        $word = 'чебуратор';

        $correction = new CorrectionEntry();
        $correction->Singular->Nominative = $word;
        $correction->Singular->Genitive = "чебурилу";
        $correction->Singular->Dative = "чербурозавру";
        $correction->Singular->Accusative = "чебурень";
        $correction->Singular->Instrumental = "чебурылом";
        $correction->Singular->Prepositional = "чебурени";
        $correction->Singular->Locative = 'в чебураторке';

        $correction->Plural->Nominative = "чебуреки";
        $correction->Plural->Genitive = "чебуроботов";
        $correction->Plural->Dative = "чебуруням";
        $correction->Plural->Accusative = "отчебурочь";
        $correction->Plural->Instrumental = "чебурулями";
        $correction->Plural->Prepositional = "чебуречках";
        $correction->Plural->Locative = 'в чебураториях';

        //1. удалить слово из словаря
        self::$testMorpher->russian->userDict->Remove($word);

        $list = self::$testMorpher->russian->userDict->GetAll();

        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $this->assertTrue(false, "Слово снова найдено в словаре после удаления.");
            }
        }

        //проверить что склонение работает стандартным образом
        $declension1 = self::$testMorpher->russian->parse($word);
        //print_r($declension1);

        $this->assertNotEquals($correction->Singular->Genitive, $declension1->Genitive);
        $this->assertNotEquals($correction->Singular->Dative, $declension1->Dative);
        $this->assertNotEquals($correction->Singular->Accusative, $declension1->Accusative);
        $this->assertNotEquals($correction->Singular->Instrumental, $declension1->Instrumental);
        $this->assertNotEquals($correction->Singular->Prepositional, $declension1->Prepositional);
        $this->assertNotEquals($correction->Singular->Locative, $declension1->Where);

        $this->assertNotEquals($correction->Plural->Nominative, $declension1->Plural->Nominative);

        $this->assertNotEquals($correction->Plural->Genitive, $declension1->Plural->Genitive);
        $this->assertNotEquals($correction->Plural->Dative, $declension1->Plural->Dative);
        $this->assertNotEquals($correction->Plural->Accusative, $declension1->Plural->Accusative);
        $this->assertNotEquals($correction->Plural->Instrumental, $declension1->Plural->Instrumental);
        $this->assertNotEquals($correction->Plural->Prepositional, $declension1->Plural->Prepositional);
        //$this->assertNotEquals($correction->Plural->Locative,$declension1->Plural->Where);
        unset($declension1);

        //добавить слово в словарь
        self::$testMorpher->russian->userDict->AddOrUpdate($correction);

        $list = self::$testMorpher->russian->userDict->GetAll();
        $found = false;
        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $found = true;

                $this->assertEquals($correction->Singular->Genitive, $item->Singular->Genitive);
                $this->assertEquals($correction->Singular->Dative, $item->Singular->Dative);
                $this->assertEquals($correction->Singular->Accusative, $item->Singular->Accusative);
                $this->assertEquals($correction->Singular->Instrumental, $item->Singular->Instrumental);
                $this->assertEquals($correction->Singular->Prepositional, $item->Singular->Prepositional);
                $this->assertEquals($correction->Singular->Locative, $item->Singular->Locative);

                $this->assertEquals($correction->Plural->Nominative, $item->Plural->Nominative);

                $this->assertEquals($correction->Plural->Genitive, $item->Plural->Genitive);
                $this->assertEquals($correction->Plural->Dative, $item->Plural->Dative);
                $this->assertEquals($correction->Plural->Accusative, $item->Plural->Accusative);
                $this->assertEquals($correction->Plural->Instrumental, $item->Plural->Instrumental);
                $this->assertEquals($correction->Plural->Prepositional, $item->Plural->Prepositional);
                $this->assertEquals($correction->Plural->Locative, $item->Plural->Locative);

            }
        }
        $this->assertTrue($found, "Слово не найдено в словаре после добавления.");

        //проверить что склонение работает по словарю
        $declension2 = self::$testMorpher->russian->parse($word);
        //print_r($declension2);

        $this->assertEquals($correction->Singular->Genitive, $declension2->Genitive);
        $this->assertEquals($correction->Singular->Dative, $declension2->Dative);
        $this->assertEquals($correction->Singular->Accusative, $declension2->Accusative);
        $this->assertEquals($correction->Singular->Instrumental, $declension2->Instrumental);
        $this->assertEquals($correction->Singular->Prepositional, $declension2->Prepositional);
        $this->assertEquals($correction->Singular->Locative, $declension2->Where);

        $this->assertEquals($correction->Plural->Nominative, $declension2->Plural->Nominative);

        $this->assertEquals($correction->Plural->Genitive, $declension2->Plural->Genitive);
        $this->assertEquals($correction->Plural->Dative, $declension2->Plural->Dative);
        $this->assertEquals($correction->Plural->Accusative, $declension2->Plural->Accusative);
        $this->assertEquals($correction->Plural->Instrumental, $declension2->Plural->Instrumental);
        $this->assertEquals($correction->Plural->Prepositional, $declension2->Plural->Prepositional);
        //$this->assertEquals($correction->Plural->Locative,$declension2->Plural->Where);

        unset($declension2);
        //удалить слово из словаря
        self::$testMorpher->russian->userDict->Remove($word);

        $list = self::$testMorpher->russian->userDict->GetAll();

        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $this->assertTrue(false, "Слово снова найдено в словаре после удаления.");
            }
        }

        //проверить что склонение работает стандартным образом
        $declension3 = self::$testMorpher->russian->parse($word);
        //print_r($declension1);

        $this->assertNotEquals($correction->Singular->Genitive, $declension3->Genitive);
        $this->assertNotEquals($correction->Singular->Dative, $declension3->Dative);
        $this->assertNotEquals($correction->Singular->Accusative, $declension3->Accusative);
        $this->assertNotEquals($correction->Singular->Instrumental, $declension3->Instrumental);
        $this->assertNotEquals($correction->Singular->Prepositional, $declension3->Prepositional);
        $this->assertNotEquals($correction->Singular->Locative, $declension3->Where);

        $this->assertNotEquals($correction->Plural->Nominative, $declension3->Plural->Nominative);

        $this->assertNotEquals($correction->Plural->Genitive, $declension3->Plural->Genitive);
        $this->assertNotEquals($correction->Plural->Dative, $declension3->Plural->Dative);
        $this->assertNotEquals($correction->Plural->Accusative, $declension3->Plural->Accusative);
        $this->assertNotEquals($correction->Plural->Instrumental, $declension3->Plural->Instrumental);
        $this->assertNotEquals($correction->Plural->Prepositional, $declension3->Plural->Prepositional);
        //$this->assertNotEquals($correction->Plural->Locative,$declension3->Plural->Where);

    }

}
