
# PHP-клиент веб-сервиса ws3.morpher.ru

[Composer package](https://packagist.org/packages/morpher/ws3-client)

## See also 

* [doctrine/inflector](https://github.com/doctrine/inflector), a popular pluralization library for English
* [Mikulas/inflection](https://github.com/Mikulas/inflection), a declension library for the Czech language

### Склонение по падежам (Морфер)
       
Библиотека реализует следующие функции
(с помощью веб-сервиса "Морфер 3.0")

#Н а русском языке:
•	Склонение по падежам;
•	Выделение в строке фамилии, имени и отчества;
•	Пропись чисел и склонение единицы измерения (3 новых письма, 10 комментариев);
•	Пропись чисел в виде порядковых числительных («сто первый километр»);
•	Пропись дат в любом падеже («пятого мая две тысячи первого года»);
•	Склонение прилагательных по родам;
•	Образование прилагательных от названий городов и стран;
•	Расстановка ударений в текстах.

# На украинском языке:
•	Склонение по падежам;
•	Пропись чисел и склонение единицы измерения (3 рубля, 10 коментарів).

# На казахском языке:
•	Склонение по падежам, числам и лицам.
# Общие:
•	Остаток запросов на данный момент.

# Пользовательский словарь
Веб-сервис поддерживает исправление склонения по требованию пользователя. Для этого имеются 3 метода:
•	Получить список всех добавленных исправлений;
•	Добавить или изменить исправление;
•	Удалить исправление.

Веб-сервис "Морфер 3.0" предусматривает бесплатное (с ограничениями) и платное использование. Подробнее смотрите на сайте проекта.

## Требования к системе
•	PHP 7.0 и выше
•	composer

## Установка
Выполните команду
    composer require morpher/ws3-client

## Использование
    <?php
    require_once __DIR__."/vendor/autoload.php";
    use Morpher\Ws3Client\Morpher;
    $base_url = 'https://ws3.morpher.ru';
    $token=”XXXXXXXXXXXXXXXXXXXXXXXXX”;
    $morpher=new Morpher($base_url,$token);

Где  $token   укажите токен, который получили при регистрации на сайте morhper.ru(https://morpher.ru)
Если токен пустой, сервис будет работать с ограничениями бесплатной версии.
Можно вызвать конструктор без аргументов, в этом случае будут использоваться параметры по умолчанию.

## Склонение по падежам на русском языке
Метод $morpher->russian->Parse($lemma,$flags) решает задачу склонения слова или словосочетания по падежам; 

Входные параметры
1.	Строка – слово или фраза на русском языке.
2.	Массив флагов (необязательный параметр). Флаги принимают значения констант из класса Morpher\Ws3Client\Russian\Flags.

    $declensionResult    =   $morpher->russian->Parse($lemma);

$declensionResult — объект Morpher\Ws3Client\Russian\DeclensionResult со следующими свойствами:
•	$declensionResult->Nominative — текст в именительном падеже;
•	$declensionResult->Genitive — текст в родительном падеже;
•	$declensionResult->Dative — текст в дательном падеже;
•	$declensionResult->Accusative — текст в винительном падеже;
•	$declensionResult->Instrumental — текст в творительном падеже;
•	$declensionResult->Prepositional — текст в предложном падеже;
•	$declensionResult->Plural — объект со свойствами-падежами для текста во множественном числе, например $declensionResult->Plural->Nominative  .

# При использовании платного аккаунта на сервисе определяются дополнительные свойства:
•	$declensionResult->PrepositionalWithO — предложный падеж с предлогом О/ОБ/ОБО, предлог выбирается автоматически;
•	$declensionResult->Gender — род. Тип – строка. Принимает значения констант из класса Morpher\Ws3Client\Russian\Gender , всего 4 варианта - Gender::Masculine, Gender::Feminine, Gender::Neuter, Gender::Plural, соответственно мужской, женский, средний и множественное число.
•	$declensionResult->Where — в местном падеже (локатив) с предлогом;
•	$declensionResult->To – куда — в направительном падеже (аллатив) с предлогом;
•	$declensionResult->From –откуда — в исходном падеже (аблатив) с предлогом.

# Флаги для разрешения неоднозначностей
Есть слова, которые могут склоняться по-разному, например:
•	фамилия Резник склоняется у мужчин и не склоняется у женщин;
•	Ростов в творительном падеже будет Ростовым, если это фамилия, и Ростовом, если это город;
•	тестер в винительном падеже будет тестера, если это человек, и тестер, если имеется в виду прибор.

Для повышения качества склонения вы можете сообщить веб-сервису дополнительную информацию через флаги. Флаги принимают значения констант из класса Morpher\Ws3Client\Russian\Flags . Флаги нужно передавать в массиве:

    use Morpher\Ws3Client\Russian\Flags;
    $morpher->russian->Parse( 'Слепов Сергей Николаевич', [Flags::Name, Flags::Masculine]);

# Флаги поддерживаемые для $morpher->russian->Parse:
•	Flags::Feminine — Женский род;
•	Flags::Masculine — Мужской род;
•	Flags::Animate — Одушевлённое;
•	Flags::Inanimate — Неодушевлённое;
•	Flags::Common — Нарицательное;
•	Flags::Name — ФИО.

## Выделение в строке фамилии, имени и отчества
Если входная строка распознана как ФИО, то объект $declensionResult->FullName будет содержать разбивку строки на фамилию, имя и отчество:
•	$declensionResult->FullName->Name - имя;
•	$declensionResult->FullName->Surname - фамилия;
•	$declensionResult->FullName->Pantronymic – отчество.

## Пропись чисел и согласование с числом
Метод $morpher->russian->Spell($number, $unit) решает задачу получения прописи числа (тысяча сто двадцать пять) и согласование единицы измерения с предшествующем числом (1 попугай, 2 попугая, 5 попугаев). Входные параметры: $number – целое число; $unit – строка. Метод возвращает объект Morpher\Ws3Client\Russian\NumberSpellingResult,
содержащий свойства NumberDeclension и UnitDeclension. Оба свойства содержат склонения по всем падежам.
    $numberSpellingResult=$morpher->russian->Spell(235, 'рубль');
    print $numberSpellingResult->NumberDeclension->Dative; //двумстам тридцати пяти
    print $numberSpellingResult->UnitDeclension->Dative; //рублям

## Пропись чисел в виде порядковых числительных
Метод $morpher->russian->SpellOrdinal($number, $unit) решает задачу прописи числа в форме порядкового числительного. Входные параметры: $number – целое число; $unit – строка.
Метод возвращает объект Morpher\Ws3Client\Russian\NumberSpellingResult.
    $numberSpellingResult =$morpher->russian->SpellOrdinal(5, 'колесо');
    print $numberSpellingResult->NumberDeclension->Dative; //пятому
    print $numberSpellingResult->UnitDeclension->Dative; //колесу

## Пропись дат
Метод $morpher->russian->SpellDate($date) решает задачу прописи даты и склонения по падежам. $date может быть передана:
- как строка в формате "2019-06-29";
- как объект реализующий DateTimeInterface (например класс DateTime)
- как timestamp (целое число).
Метод возвращает объект Morpher\Ws3Client\Russian\DateSpellingResult.

    $dateSpellingResult = $morpher->russian->SpellDate('2019-06-29');
    print  $dateSpellingResult->Genitive;  // двадцать девятого июня две тысячи девятнадцатого года
    print  $dateSpellingResult->Dative; // двадцать девятому июня две тысячи девятнадцатого года
    print  $dateSpellingResult->Instrumental;    // двадцать девятым июня две тысячи девятнадцатым годом

## Склонение прилагательных по родам
Метод $morpher->russian->AdjectiveGenders($adjective) склоняет данное ему прилагательное, преобразуя его из мужского рода в женский, средний и во множественное число. Входной параметр – строка. Метод возвращает объект Morpher\Ws3Client\Russian\AdjectiveGenders.

    $adjectiveGenders =$morpher->russian->AdjectiveGenders('уважаемый');

    print $adjectiveGenders->Feminine;      // уважаемая
    print $adjectiveGenders->Neuter;        // уважаемое
    print $adjectiveGenders->Plural;        // уважаемые

# Требования к входному прилагательному:
•	Оно должно быть в мужском роде, в единственном числе.
•	Оно должно быть полным, т.е. "полный", а не "полон".
•	Оно должно быть одним словом. Внутри слова допустимы дефис и апостроф: рабоче-крестьянский, Кот-д'Ивуарский. Вокруг слова допустимы пробелы, кавычки и другие знаки.

## Образование прилагательных
Метод $morpher->russian->Adjectivize($lemma) образует прилагательные от названий городов и стран: Москва – московский, Ростов – ростовский, Швеция – шведский, Греция – греческий. Входной параметр – строка. Метод возвращает массив строк. Что они означают, описано здесь(https://morpher.ru/adjectivizer/).
Пример:
    $adjectives=$morpher->russian->Adjectivize('Москва');
    print   $adjectives[0]; // московский

## Расстановка ударений в текстах
Метод $morpher->russian->addStressMarks($text) расставляет ударения в текстах на русском языке. Входной параметр – строка. Метод возвращает строку аналогичную входной, но дополненную символами ударения. Строки могут быть большой длины.
    $result=$morpher->russian->addStressMarks('Три девицы под окном');
    print $result;// Три деви́цы под окно́м

Ударение отмечается символом с кодом U+0301, который вставляется сразу после ударной гласной. Односложные слова не получают знака ударения, за исключением случаев, когда предлог или частица несет на себе ударение: за́ руку, не́ за что. Варианты прочтения разделяются вертикальной чертой, например:
    $result=$morpher->russian->addStressMarks('Белки питаются белками');
    print $result; // Бе́лки|Белки́ пита́ются бе́лками|белка́ми

## Склонение по падежам на украинском языке

Украинский вариант склонения — метод $morpher->ukrainian->Parse($lemma,$flags);
Входные параметры:
1.  Строка, содержащая слово или фразу на украинском языке. 
2. Массив флагов (необязательный), принимающих значения констант из класса Morpher\Ws3Client\Ukrainian\Flag. 
Метод возвращает объект Morpher\Ws3Client\Ukrainian\DeclensionResult.
    $declensionResult=$morpher->ukrainian->Parse('Крутько Катерина Володимирiвна');

    print   $declensionResult->Genitive; // Крутько Катерини Володимирівни
    print   $declensionResult->Dative;  // Крутько Катерині Володимирівні
    print   $declensionResult->Vocative;   // Крутько Катерино Володимирівно

Объект Morpher\Ws3Client\Ukrainian\DeclensionResult имеет следующие свойства:
•	Nominative — текст в именительном падеже;
•	Genitive — текст в родительном падеже;
•	Dative — текст в дательном падеже;
•	Aaccusative — текст в винительном падеже;
•	Instrumental — текст в творительном падеже;
•	Prepositional — текст в местном падеже;
•	Vocative — текст в звательном падеже.

# При использовании платного аккаунта на сервисе, определяются дополнительные свойства:
•	Gender — род, тип – строка, принимает значения констант из класса Morpher\Ws3Client\Ukrainian\Gender, варианты – Gender::Masculine (Чоловічий), Gender::Feminine (Жіночий), Gender::Neuter (Середній), Gender::Plural (Множина).

# Флаги для разрешения неоднозначностей
$declensionResult=$morpher->ukrainian->Parse('Карен', [Flag::Feminine]);
print $declensionResult->Genitive; // Карен (женское имя не склоняется)

Флаги поддерживаемые для $morpher->ukrainian->Parse($lemma,$flags):
•	Flag::Feminine — Женский род;
•	Flag::Masculine — Мужской род;
•	Flag::Neuter — Средний род;
•	Flag::Plural — Множественное число.

## Пропись чисел и согласование с числом на украинском языке
Метод $morpher->ukrainian->Spell($number, $unit) решает задачу получения прописи числа (одна тисяча сто двадцять п'ять) и согласование единицы измерения с предшествующем числом (один рубль, два рубля, п'ять рублів). Входные параметры: $number – целое число; $unit – строка. Метод возвращает объект Morpher\Ws3Client\Ukrainian\NumberSpellingResult,
содержащий свойства NumberDeclension и UnitDeclension. Оба свойства содержат склонения по всем падежам.
    $spellingResult=$morpher->ukrainian->Spell(235, 'рубль');
    print $spellingResult->NumberDeclension->Genitive;    // двохсот тридцяти п'яти
    print $spellingResult->UnitDeclension->Genitive; // рублів

## Склонение по падежам, числам и лицам на казахском языке
Для склонения слов и словосочетаний используется метод $morpher->qazaq->Parse($phrase).
Входной параметр – срока, слово или фраза на казахском языке. Метод возвращает объект 
Morpher\Ws3Client\Qazaq\DeclensionResult.
Объект имеет сложную структуру.
Этот объект содержит 7 падежей, а также 8 лицевых форм склонений единственного числа, и каждая в себе содержит 7 падежей.
    $declensionResult->Genitive
    $declensionResult->FirstPerson->Genitive;
    $declensionResult->SecondPerson->Accusative;
    …
    $declensionResult->ThirdPersonPlura->Dative;
А также содержит объект Plural, в котором 7 падежей множественного числа, и ещё 8 лицевых форм склонений множественного числа, каждая себе содержит 7 падежей:
    $declensionResult->Plural->Locative
    $declensionResult->Plural->FirstPerson->Locative;
    $declensionResult->Plural->SecondPerson->Nominative;
    …
    $declensionResult->Plural->ThirdPersonPlural->Dative;

Пример:
    $declensionResult = $morpher->qazaq->Parse('менеджер');

    print $declensionResult->Genitive;      // менеджердің
    print $declensionResult->Plural->Genitive;  // менеджерлердің
    print $declensionResult->Plural->FirstPerson->Genetive; // менеджерлеріміздің

# Свойства объекта Morpher\Ws3Client\Qazaq\DeclensionResult:
Свойства-Падежи ед. числа:
•	Nominative  - атау — текст в именительном падеже;
•	Genitive - ілік — текст в родительном падеже;
•	Dative - барыс — текст в дательно-направительном падеже;
•	Accusative - табыс — текст в винительном падеже;
•	Ablative - шығыс — текст в исходном падеже;
•	Locative - жатыс — текст в местном падеже;
•	Instrumental - көмектес — текст в творительном падеже;
Свойства – лицевые формы ед. числа (в каждой свои падежи):
FirstPerson - "менің"
SecondPerson -  "сенің"
SecondPersonRespectful - "сіздің"
ThirdPerson - "оның"
FirstPersonPlural - "біздің"
SecondPersonPlural - "сендердің"
SecondPersonRespectfulPlural - "сіздердің"
ThirdPersonPlural - "олардың"

Свойство множественного числа:
•	Plural - көпше — возвращает аналогичный объект со свойствами-падежами и свойствами-лицевыми формами для текста во множественном числе.

## Остаток запросов
Метод $morpher->getQueriesLeftForToday() возвращает остаток запросов на данный момент. Лимит на запросы восстанавливается в 00:00 UTC.
    print  $morpher->getQueriesLeftForToday(); // 939

## Получить список исправлений
Для того чтобы получить список всех исправлений, нужно использовать метод 
    $rus=$morpher->russian->UserDict->GetAll(); // Morpher\Ws3Client\Russian\СorrectionEntry
    $ukr=$morpher->ukrainian->UserDict->GetAll(); // Morpher\Ws3Client\Ukrainian\СorrectionEntry

Метод возвращает массив объектов СorrectionEntry в пространстве имён соответствующего языку (русскому, украинскому).

# Для русского языка:
Объект Morpher\Ws3Client\Russian\СorrectionEntry со следующими свойствами:
•	singular — объект Morpher\Ws3Client\Russian\CorrectionForms с формами в единственном числе;
•	plural — объект Morpher\Ws3Client\Russian\CorrectionForms с формами во множественном числе;
Указание рода не поддерживается.

Объект Morpher\Ws3Client\Russian\CorrectionForms со следующими свойствами:
•	именительный (Nominative) — текст в именительном падеже;
•	родительный (Genitive) — текст в родительном падеже;
•	дательный (Dative) — текст в дательном падеже;
•	винительный (Accusative) — текст в винительном падеже;
•	творительный (Instrumental) — текст в творительном падеже;
•	предложный (Prepositional) — текст в предложном падеже;
•	местный (Locative) — текст в местном падеже;]

# Для украинского языка:
Объект Morpher\Ws3Client\Ukrainian\СorrectionEntry со следующими свойствами:
•	singular — объект Morpher\Ws3Client\Ukrainian\CorrectionForms с формами в единственном числе;
Указание рода не поддерживается.

Объект Morpher\Ws3Client\Ukrainian\CorrectionForms со следующими свойствами:
•	називний (Nominative) — текст в именительном падеже;
•	родовий (Genitive) — текст в родительном падеже;
•	давальний (Dative) — текст в дательном падеже;
•	знахідний (Accusative) — текст в винительном падеже;
•	орудний (Instrumental) — текст в творительном падеже;
•	місцевий (Prepositional) — текст в местном падеже;
•	кличний (Vocative) — текст в звательном падеже.

## Добавить или изменить исправление
Для добавления или изменения исправления использовать метод 
$morpher->russian->UserDict->AddOrUpdate($entry),
или аналогично $morpher->ukrainian->UserDict->AddOrUpdate($entry):

    $correction=new \Morpher\Ws3Client\Russian\CorrectionEntry();
    $correction->Singular->Nominative=”чебуратор”;
    $correction->Singular->Locative='в чебураторке';
    $correction->Plural->Locative='в чебураториях';
    $morpher->russian->UserDict->AddOrUpdate($entry);

## Удаление исправления
Для того чтобы удалить исправление, достаточно передать строку в именительном падеже в метод 
    $morpher->russian->UserDict->Remove($nominativeForm); 
или аналогично 
    $morpher->ukrainian->UserDict->Remove($nominativeForm);

Пример:
    $morpher->russian->UserDict->Remove(“чебуратор”);

## Разработка
Должны быть установлены PHP 7.0 или выше; Composer.

# Установка
Сделайте форк репозитория morpher-ws3-js-client.
Затем выполните:
    $ git clone https://github.com/<your-github-username>/morpher-ws3-js-client
    $ cd morpher-ws3-js-client
    $ composer install
Должна появиться папка vendor

# Запуск тестов

Запуск юнит теста:
    $ vendor\bin\phpunit  tests\unit

Integration:
Для запуска интеграционных тестов задать секретный токен, иначе тесты частично будут выполнены с ошибкой.
Есть два способа задать токен:
1)	Подходит для локального запуска. Создать файл secret.php , в котором объявить константу:
    <?php
    DEFINE("MORPHER_RU_TOKEN" ,"xxxxx-xxxxxx-xxxxxxx");

2)	Подходит для запуска в контейнере GitHub Actions. В GitHub Actions, в разделе Secrets, создать переменную окружения MORPHER_RU_TOKEN, и сохранить токен в неё.

Запуск интеграционного теста:
    $ vendor\bin\phpunit  tests\integration

# Обновление зависимостей:
    $ composer update

# Обновление автозагрузки классов composer autoload (после каждого создания нового php файла в проекте):
    $ composer dump-autoload -o

# Выпуск нового релиза
•	Увеличить версию в composer.json.
•	Добавить новый релиз на Гитхабе.

Новый пакет появится https://packagist.org автоматически, если в личном кабинете на https://packagist.org было настроено разрешение на автообновление с вашего GitHub.

