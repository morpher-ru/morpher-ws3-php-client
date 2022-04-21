<?php declare(strict_types=1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Ukrainian as Ukrainian;
use Morpher\Ws3Client\Ukrainian\CorrectionForms;
use Morpher\Ws3Client\Ukrainian\CorrectionEntry;
use Morpher\Ws3Client\Ukrainian\Gender;
use Morpher\Ws3Client\Ukrainian\UserDict;

final class UkrainianUserDictTest extends IntegrationBase
{
    public function testUserDict_Success(): void
    {
        $word='чебуратор';
        self::$testMorpher->ukrainian->userDict->Remove($word);

        $list=self::$testMorpher->ukrainian->userDict->GetAll();

        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $this->assertTrue(false,"Слово снова найдено в словаре после удаления.");
            }
        }
        
        $correction=new CorrectionEntry();
        $correction->Singular->Nominative=$word;
        $correction->Singular->Vocative='в чебураторке';

        self::$testMorpher->ukrainian->userDict->AddOrUpdate($correction);

        $list=self::$testMorpher->ukrainian->userDict->GetAll();
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
                $this->assertEquals('в чебураторке', $item->Singular->Vocative,"Не удалось добавление косвенной формы в словаре");
            }
        }       

        $this->assertTrue($found,"Слово не найдено в словаре после добавления.");

        $correction=new CorrectionEntry();
        $correction->Singular->Nominative=$word;
        $correction->Singular->Vocative='в чебурелии';

        self::$testMorpher->ukrainian->userDict->AddOrUpdate($correction);

        $list=self::$testMorpher->ukrainian->userDict->GetAll();
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
                $this->assertEquals('в чебурелии', $item->Singular->Vocative,"Не удалось обновление косвенной формы в словаре");
            }
        }

        $this->assertTrue($found,"Слово не найдено в словаре после обновления.");

        self::$testMorpher->ukrainian->userDict->Remove($word);

        $list=self::$testMorpher->ukrainian->userDict->GetAll();

        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $this->assertTrue(false,"Слово снова найдено в словаре после удаления.");
            }
        }
    }

/* 
    public function testUserDictGender_Success(): void
    {
        $word='чебуратор';
        self::$testMorpher->ukrainian->userDict->Remove($word);

        $list=self::$testMorpher->ukrainian->userDict->GetAll();

        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $this->assertTrue(false,"Слово снова найдено в словаре после удаления.");
            }
        }
        
        $correction=new CorrectionEntry();
        $correction->Singular->Nominative=$word;
        $correction->Singular->Vocative='в чебураторке';

        $correction->Gender=Gender::Feminine;

        self::$testMorpher->ukrainian->userDict->AddOrUpdate($correction);

        $list=self::$testMorpher->ukrainian->userDict->GetAll();
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
                $this->assertEquals('в чебураторке', $item->Singular->Vocative,"Не удалось добавление косвенной формы в словаре");
     
                 $this->assertEquals(Gender::Feminine,$item->Gender); 
            }
        }       
        $this->assertTrue($found,"Слово не найдено в словаре после добавления.");



        $correction=new CorrectionEntry();
        $correction->Singular->Nominative=$word;
        $correction->Singular->Vocative='в чебурелии';
         $correction->Gender=Gender::Masculine;

        self::$testMorpher->ukrainian->userDict->AddOrUpdate($correction);

        $list=self::$testMorpher->ukrainian->userDict->GetAll();
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
                $this->assertEquals('в чебурелии', $item->Singular->Vocative,"Не удалось обновление косвенной формы в словаре");
     

                $this->assertEquals(Gender::Masculine,$item->Gender); 
            }
        }       
        $this->assertTrue($found,"Слово не найдено в словаре после обновления.");

        self::$testMorpher->ukrainian->userDict->Remove($word);

        $list=self::$testMorpher->ukrainian->userDict->GetAll();

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
        $word='чебуратор';

        $correction=new CorrectionEntry();
        $correction->Singular->Nominative=$word;
        $correction->Singular->Genitive="чебурилу";
        $correction->Singular->Dative="чербурозавру";
        $correction->Singular->Accusative="чебурень";
        $correction->Singular->Instrumental="чебурылом";
        $correction->Singular->Prepositional="чебурени";
        $correction->Singular->Vocative='в чебураторке';

        //1. удалить слово из словаря
        self::$testMorpher->ukrainian->userDict->Remove($word);

        $list=self::$testMorpher->ukrainian->userDict->GetAll();

        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $this->assertTrue(false, "Слово снова найдено в словаре после удаления.");
            }
        }

        //проверить что склонение работает стандартным образом
        $declension1=self::$testMorpher->ukrainian->parse($word);
        //print_r($declension1);
        
        $this->assertNotEquals($correction->Singular->Genitive,$declension1->Genitive); 
        $this->assertNotEquals($correction->Singular->Dative,$declension1->Dative);
        $this->assertNotEquals($correction->Singular->Accusative,$declension1->Accusative); 
        $this->assertNotEquals($correction->Singular->Instrumental,$declension1->Instrumental); 
        $this->assertNotEquals($correction->Singular->Prepositional,$declension1->Prepositional); 
        $this->assertNotEquals($correction->Singular->Vocative, $declension1->Vocative);

        unset($declension1);

        //добавить слово в словарь
        self::$testMorpher->ukrainian->userDict->AddOrUpdate($correction);

        $list=self::$testMorpher->ukrainian->userDict->GetAll();
        $found=false;
        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $found=true;

                $this->assertEquals($correction->Singular->Genitive,$item->Singular->Genitive); 
                $this->assertEquals($correction->Singular->Dative,$item->Singular->Dative);
                $this->assertEquals($correction->Singular->Accusative,$item->Singular->Accusative); 
                $this->assertEquals($correction->Singular->Instrumental,$item->Singular->Instrumental); 
                $this->assertEquals($correction->Singular->Prepositional,$item->Singular->Prepositional); 
                $this->assertEquals($correction->Singular->Vocative, $item->Singular->Vocative);
            }
        }       
        $this->assertTrue($found,"Слово не найдено в словаре после добавления.");

        //проверить что склонение работает по словарю
        $declension2=self::$testMorpher->ukrainian->parse($word);
        //print_r($declension2);

        $this->assertEquals($correction->Singular->Genitive,$declension2->Genitive); 
        $this->assertEquals($correction->Singular->Dative,$declension2->Dative);
        $this->assertEquals($correction->Singular->Accusative,$declension2->Accusative); 
        $this->assertEquals($correction->Singular->Instrumental,$declension2->Instrumental); 
        $this->assertEquals($correction->Singular->Prepositional,$declension2->Prepositional); 
        $this->assertEquals($correction->Singular->Vocative, $declension2->Vocative);

        unset($declension2);

        //удалить слово из словаря
        self::$testMorpher->ukrainian->userDict->Remove($word);

        $list=self::$testMorpher->ukrainian->userDict->GetAll();

        foreach ($list as $item)
        {
            if ($item->Singular->Nominative == $word)
            {
                $this->assertTrue(false,"Слово снова найдено в словаре после удаления.");
            }
        }

        //проверить что склонение работает стандартным образом
        $declension3=self::$testMorpher->ukrainian->parse($word);
        //print_r($declension1);
        
        $this->assertNotEquals($correction->Singular->Genitive,$declension3->Genitive); 
        $this->assertNotEquals($correction->Singular->Dative,$declension3->Dative);
        $this->assertNotEquals($correction->Singular->Accusative,$declension3->Accusative); 
        $this->assertNotEquals($correction->Singular->Instrumental,$declension3->Instrumental); 
        $this->assertNotEquals($correction->Singular->Prepositional,$declension3->Prepositional); 
        $this->assertNotEquals($correction->Singular->Vocative, $declension3->Vocative);
    }
}