# PHP-клиент веб-сервиса ws3.morpher.ru  
  
[Composer package](https://packagist.org/packages/morpher/ws3-client)  
  
### Склонение по падежам (Морфер)  
         
Библиотека реализует следующие функции  
(с помощью веб-сервиса "Морфер 3.0")  
  
## На русском языке:  
  * [Склонение по падежам;](#rusdecl)  
  * [Выделение в строке фамилии, имени и отчества;](#rusfio)  
  * [Пропись чисел и склонение единицы измерения (3 новых письма, 10 комментариев);](#russpell)  
  * [Пропись чисел в виде порядковых числительных («сто первый километр»);](#russpellord)  
  * [Пропись дат в любом падеже («пятого мая две тысячи первого года»);](#rusdate)  
  * [Склонение прилагательных по родам;](#rusadjgend)  
  * [Образование прилагательных от названий городов и стран;](#rusadj)  
  * [Расстановка ударений в текстах.](#russtress)  
  * [Пользовательский словарь для исправлений](#userdict)  
  
## На украинском языке:  
  * [Склонение по падежам;](#ukrdecl)  
  * [Пропись чисел и склонение единицы измерения (3 рубля, 10 коментарів).](#ukrspell)  
  * [Пользовательский словарь для исправлений](#userdict)  
  
## На казахском языке:  
  * [Склонение по падежам, числам и лицам.](#qazaqdecl)  
## Общие:  
  * [Остаток запросов на данный момент.](#queriesleft)  

Веб-сервис "Морфер 3.0" предусматривает бесплатное (с ограничениями) и платное использование. Подробнее смотрите на [сайте проекта](https://morpher.ru/ws3/#limitations).  
  
## Требования к системе  
  * PHP 7.4 и выше  
  * composer  
  
## Установка 

Если в вашем проекте отсутствует файл composer.json,
то необходимо выполнить:

    $ composer init
  
И ответить на вопросы программы (название проекта и т.д.). Обратите внимание на параметр ``minimum-stability`` - он должен быть не выше чем указано в той версии пакета morpher которую вы будете устанавливать в свой проект. Если не получилось установить пакет, попробуйте задать в файле composer.json :    ``"minimum-stability": "dev"``.   

Файл composer.json и папка vendor будут созданы.  
Выполните команду:

    $  composer require morpher/ws3-client  

## Использование  

    <?php  
    require_once __DIR__."/vendor/autoload.php";  
    use Morpher\Ws3Client\Morpher;  
    $base_url = 'https://ws3.morpher.ru';  
    $token="";  
    $morpher=new Morpher($base_url,$token);  
    $declensionResult  = $morpher->russian->parse('трамвай');
    print_r($declensionResult);
  
Где  ``$token="";``   в кавычках укажите токен, который получили при регистрации на сайте https://morpher.ru  .  
Если токен пустой, сервис будет работать с ограничениями бесплатной версии.  
Можно вызвать конструктор без аргументов, в этом случае будут использоваться параметры по умолчанию.  

## <a name="rusdecl"></a>Склонение по падежам на русском языке     
Метод ``$morpher->russian->parse($lemma,$flags)`` решает задачу склонения слова или словосочетания по падежам;   
  
Входные параметры  
1.	Строка – слово или фраза на русском языке.  
2.	Массив флагов (необязательный параметр). Флаги принимают значения констант из класса ``Morpher\Ws3Client\Russian\Flags``.  

Пример:  
  
    $lemma = 'фраза на русском';  
    $declensionResult = $morpher->russian->parse($lemma);  
  
$declensionResult — объект ``Morpher\Ws3Client\Russian\DeclensionResult`` со следующими свойствами:  
  * $declensionResult->nominative — текст в именительном падеже;  
  * $declensionResult->genitive — текст в родительном падеже;  
  * $declensionResult->dative — текст в дательном падеже;  
  * $declensionResult->accusative — текст в винительном падеже;  
  * $declensionResult->instrumental — текст в творительном падеже;  
  * $declensionResult->prepositional — текст в предложном падеже;  
  * $declensionResult->plural — объект со свойствами-падежами для текста во множественном числе, например $declensionResult->plural->nominative  .  
  
При использовании платного аккаунта на сервисе определяются дополнительные свойства:

  * $declensionResult->prepositionalWithO — предложный падеж с предлогом О/ОБ/ОБО, предлог выбирается автоматически;  
  * $declensionResult->gender — род. Тип – строка. Принимает значения констант из класса ``Morpher\Ws3Client\Russian\Gender`` , всего 4 варианта -  
    * ``Gender::MASCULINE``  мужской  
    * ``Gender::FEMININE``  женский  
    * ``Gender::NEUTER``  средний  
    * ``Gender::PLURAL``  множественное число  
  * $declensionResult->where — в местном падеже (локатив) с предлогом;  
  * $declensionResult->to – куда — в направительном падеже (аллатив) с предлогом;  
  * $declensionResult->from – откуда — в исходном падеже (аблатив) с предлогом.  
  
# Флаги для разрешения неоднозначностей  
Есть слова, которые могут склоняться по-разному, например:  
  * фамилия Резник склоняется у мужчин и не склоняется у женщин;  
  * Ростов в творительном падеже будет Ростовым, если это фамилия, и Ростовом, если это город;  
  * тестер в винительном падеже будет тестера, если это человек, и тестер, если имеется в виду прибор.  
  
Для повышения качества склонения вы можете сообщить веб-сервису дополнительную информацию через флаги. Флаги принимают значения констант из класса ``Morpher\Ws3Client\Russian\Flags`` . Флаги нужно передавать в массиве:  
  
    use Morpher\Ws3Client\Russian\Flags;  
    $morpher->russian->parse( 'Слепов Сергей Николаевич', [Flags::NAME, Flags::MASCULINE]);  
  
# Флаги для ``$morpher->russian->parse``:  
  * Flags::FEMININE — Женский род;  
  * Flags::MASCULINE — Мужской род;  
  * Flags::ANIMATE — Одушевлённое;  
  * Flags::INANIMATE — Неодушевлённое;  
  * Flags::COMMON — Нарицательное;  
  * Flags::NAME — ФИО.
  
## <a name="rusfio"></a>Выделение в строке фамилии, имени и отчества  
Если входная строка распознана как ФИО, то объект ``$declensionResult->fullName`` будет содержать разбивку строки на фамилию, имя и отчество:  
  * $declensionResult->fullName->name - имя;  
  * $declensionResult->fullName->surname - фамилия;  
  * $declensionResult->fullName->patronymic – отчество.  
  
## <a name="russpell"></a>Пропись чисел и согласование с числом  

Метод ``$morpher->russian->spell($number, $unit)`` решает задачу получения прописи числа (тысяча сто двадцать пять) и согласование единицы измерения с предшествующем числом (1 попугай, 2 попугая, 5 попугаев).   

Входные параметры:

  * $number – целое число;
  * $unit – строка. 

Метод возвращает объект ``Morpher\Ws3Client\Russian\NumberSpellingResult``,  
содержащий свойства numberDeclension и unitDeclension.
Оба свойства содержат склонения по всем падежам:

    $numberSpellingResult = $morpher->russian->spell(235, 'рубль');  
    print $numberSpellingResult->numberDeclension->dative; // двумстам тридцати пяти  
    print $numberSpellingResult->unitDeclension->dative; // рублям  
  
## <a name="russpellord"></a>Пропись чисел в виде порядковых числительных

Метод $morpher->russian->spellOrdinal($number, $unit) решает задачу прописи числа в форме порядкового числительного.

Входные параметры:

  * $number – целое число;   
  * $unit – строка.   

Метод возвращает объект ``Morpher\Ws3Client\Russian\NumberSpellingResult``. Пример:

    $numberSpellingResult = $morpher->russian->spellOrdinal(5, 'колесо');  
    print $numberSpellingResult->numberDeclension->dative; //пятому  
    print $numberSpellingResult->unitDeclension->dative; //колесу  
  
## <a name="rusdate"></a>Пропись дат

Метод ``$morpher->russian->spellDate($date)`` решает задачу прописи даты и склонения по падежам. Дата может быть передана:

- как строка в формате "2019-06-29";  
- как объект реализующий DateTimeInterface (например класс DateTime)  
- как timestamp (целое число).

Метод возвращает объект ``Morpher\Ws3Client\Russian\DateSpellingResult``. Пример:

    $dateSpellingResult = $morpher->russian->spellDate('2019-06-29');  
    print $dateSpellingResult->genitive;  // двадцать девятого июня две тысячи девятнадцатого года  
    print $dateSpellingResult->dative; // двадцать девятому июня две тысячи девятнадцатого года  
    print $dateSpellingResult->instrumental; // двадцать девятым июня две тысячи девятнадцатого года
  
## <a name="rusadjgend"></a>Склонение прилагательных по родам

Метод ``$morpher->russian->adjectiveGenders($adjective)`` склоняет данное ему прилагательное, преобразуя его из мужского рода в женский, средний и во множественное число.  

Входной параметр – строка, прилагательное. Требования к входному прилагательному:

  * Оно должно быть в мужском роде, в единственном числе.  
  * Оно должно быть полным, т.е. "полный", а не "полон".  
  * Оно должно быть одним словом. Внутри слова допустимы дефис и апостроф: рабоче-крестьянский, Кот-д'Ивуарский. Вокруг слова допустимы пробелы, кавычки и другие знаки.  

Метод возвращает объект ``Morpher\Ws3Client\Russian\AdjectiveGenders``:
  
    $adjectiveGenders =$morpher->russian->adjectiveGenders('уважаемый');  
  
    print $adjectiveGenders->feminine;      // уважаемая  
    print $adjectiveGenders->neuter;        // уважаемое  
    print $adjectiveGenders->plural;        // уважаемые  
  
## <a name="rusadj"></a>Образование прилагательных

Метод ``$morpher->russian->adjectivize($lemma)`` образует прилагательные от названий городов и стран: Москва – московский, Ростов – ростовский, Швеция – шведский, Греция – греческий. Входной параметр – строка. Метод возвращает массив строк. Что они означают, описано [здесь](https://morpher.ru/adjectivizer/).  
Пример:  

    $adjectives = $morpher->russian->adjectivize('Москва');  
    print $adjectives[0]; // московский  
  
## <a name="russtress"></a>Расстановка ударений в текстах

Метод ``$morpher->russian->addStressMarks($text)`` расставляет ударения в текстах на русском языке.
Входной параметр – строка. Метод возвращает строку аналогичную входной, но дополненную символами ударения и точками над Ё.
Строки могут быть большой длины.

    $result = $morpher->russian->addStressMarks('Три девицы под окном');  
    print $result; // Три деви́цы под окно́м  
  
Ударение отмечается символом с кодом ``U+0301``, который вставляется сразу после ударной гласной. Односложные слова не получают знака ударения, за исключением случаев, когда предлог или частица несет на себе ударение: за́ руку, не́ за что. Варианты прочтения разделяются вертикальной чертой, например:  

    $result = $morpher->russian->addStressMarks('Белки питаются белками');  
    print $result; // Бе́лки|Белки́ пита́ются бе́лками|белка́ми  
  
## <a name="ukrdecl"></a>Склонение по падежам на украинском языке  
  
Украинский вариант склонения — метод ``$morpher->ukrainian->parse($lemma,$flags)``.

Входные параметры:

1. Строка, содержащая слово или фразу на украинском языке.   
2. Массив флагов (необязательный), принимающих значения констант из класса ``Morpher\Ws3Client\Ukrainian\Flag``.   

Метод возвращает объект ``Morpher\Ws3Client\Ukrainian\DeclensionResult``:

    $declensionResult=$morpher->ukrainian->parse('Крутько Катерина Володимирiвна');  
  
    print $declensionResult->genitive; // Крутько Катерини Володимирівни  
    print $declensionResult->dative;   // Крутько Катерині Володимирівні  
    print $declensionResult->vocative; // Крутько Катерино Володимирівно  
  
Объект ``Morpher\Ws3Client\Ukrainian\DeclensionResult`` имеет следующие свойства:

  * nominative — текст в именительном падеже;  
  * genitive — текст в родительном падеже;  
  * dative — текст в дательном падеже;  
  * accusative — текст в винительном падеже;  
  * instrumental — текст в творительном падеже;  
  * prepositional — текст в местном падеже;  
  * vocative — текст в звательном падеже.  
  
При платном доступе возвращаются дополнительные свойства:

  * Gender — род, тип – строка, принимает значения констант из класса ``Morpher\Ws3Client\Ukrainian\Gender``, варианты:
    * ``Gender::MASCULINE`` (Чоловічий)
    * ``Gender::FEMININE`` (Жіночий)
    * ``Gender::NEUTER``  (Середній)  
    * ``Gender::PLURAL``  (Множина)  
  
# Флаги для разрешения неоднозначностей  

Пример:

    use Morpher\Ws3Client\Ukrainian\Flags;
    $declensionResult = $morpher->ukrainian->parse('Карен', [Flags::FEMININE]);  
    print $declensionResult->genitive; // Карен (женское имя не склоняется)  
  
Флаги поддерживаемые для ``$morpher->ukrainian->parse($lemma,$flags)``:

  * ``Flags::FEMININE`` — женский род  
  * ``Flags::MASCULINE`` — мужской род  
  * ``Gender::NEUTER``  - средний род  
  * ``Gender::PLURAL``  - множественное число  
  
## <a name="ukrspell"></a>Пропись чисел и согласование с числом на украинском языке

Метод ``$morpher->ukrainian->spell($number, $unit)`` решает задачу получения прописи числа (одна тисяча сто двадцять п'ять) и согласование единицы измерения с предшествующем числом (один рубль, два рубля, п'ять рублів).  
Входные параметры:  
* $number – целое число;  
* $unit – строка.  
   
Метод возвращает объект ``Morpher\Ws3Client\Ukrainian\NumberSpellingResult``,  
содержащий свойства ``numberDeclension`` и ``unitDeclension``. Оба свойства содержат склонения по всем падежам:

    $spellingResult = $morpher->ukrainian->spell(235, 'рубль');  
    print $spellingResult->numberDeclension->genitive; // двохсот тридцяти п'яти  
    print $spellingResult->unitDeclension->genitive;   // рублів  
  
## <a name="qazaqdecl"></a>Склонение по падежам, числам и лицам на казахском языке

Для склонения слов и словосочетаний используется метод ``$morpher->qazaq->parse($phrase)``.  
Входной параметр – срока, слово или фраза на казахском языке. Метод возвращает объект   
``Morpher\Ws3Client\Qazaq\DeclensionResult``.

Пример:  

    $declensionResult = $morpher->qazaq->parse('бала');
    print_r($declensionResult);

Объект имеет сложную структуру.  
  
Этот объект содержит 7 падежей, а также 8 лицевых форм склонений единственного числа, и каждая в себе содержит 7 падежей.

    $declensionResult->genitive  
    $declensionResult->firstPerson->genitive  
    $declensionResult->secondPerson->accusative  
    …  
    $declensionResult->thirdPersonPlural->dative  

А также содержит объект plural, в котором 7 падежей множественного числа, и ещё 8 лицевых форм склонений множественного числа, каждая себе содержит 7 падежей:

    $declensionResult->plural->locative  
    $declensionResult->plural->firstPerson->locative  
    $declensionResult->plural->secondPerson->nominative  
    …  
    $declensionResult->plural->thirdPersonPlural->dative  
  
Пример:  

    $declensionResult = $morpher->qazaq->parse('менеджер');  
  
    print $declensionResult->genitive;      // менеджердің  
    print $declensionResult->plural->genitive;  // менеджерлердің  
    print $declensionResult->plural->firstPerson->genitive; // менеджерлеріміздің  
  
# Свойства объекта Morpher\Ws3Client\Qazaq\DeclensionResult:  
Свойства-Падежи ед. числа:  
  * nominative  - атау — текст в именительном падеже;  
  * genitive - ілік — текст в родительном падеже;  
  * dative - барыс — текст в дательно-направительном падеже;  
  * accusative - табыс — текст в винительном падеже;  
  * ablative - шығыс — текст в исходном падеже;  
  * locative - жатыс — текст в местном падеже;  
  * instrumental - көмектес — текст в творительном падеже;

Свойства – лицевые формы ед. числа (в каждой свои падежи):

- firstPerson - "менің"  
- secondPerson -  "сенің"  
- secondPersonRespectful - "сіздің"  
- thirdPerson - "оның"  
- firstPersonPlural - "біздің"  
- secondPersonPlural - "сендердің"  
- secondPersonRespectfulPlural - "сіздердің"  
- thirdPersonPlural - "олардың"  
  
Свойство множественного числа:  

  * plural - көпше — возвращает аналогичный объект со свойствами-падежами и свойствами-лицевыми формами для текста во множественном числе.  
  
## <a name="queriesleft"></a>Остаток запросов

Метод ``$morpher->getQueriesLeftForToday()`` возвращает остаток запросов на данный момент. Лимит на запросы восстанавливается в 00:00 UTC.  
         
    print  $morpher->getQueriesLeftForToday(); // 939    

# <a name="userdict"></a>Пользовательский словарь  
Веб-сервис поддерживает исправление склонения по требованию пользователя. Для этого имеются 3 метода:  
  * Получить список всех добавленных исправлений;  
  * Добавить или изменить исправление;  
  * Удалить исправление.  
  
## Получить список исправлений

Для того чтобы получить список всех исправлений, нужно использовать методы:

    $rus=$morpher->russian->userDict->getAll();   // Morpher\Ws3Client\Russian\СorrectionEntry  
    $ukr=$morpher->ukrainian->userDict->getAll(); // Morpher\Ws3Client\Ukrainian\СorrectionEntry  
  
Метод возвращает массив объектов CorrectionEntry в пространстве имён соответствующего языку (русскому, украинскому).  
  
### Для русского языка:

Объект ``Morpher\Ws3Client\Russian\СorrectionEntry`` со следующими свойствами:

  * ``singular`` — объект ``Morpher\Ws3Client\Russian\CorrectionForms`` с формами в единственном числе;  
  * ``plural`` — объект ``Morpher\Ws3Client\Russian\CorrectionForms`` с формами во множественном числе;  

Указание рода не поддерживается.
  
Объект ``Morpher\Ws3Client\Russian\CorrectionForms`` со следующими свойствами:  
  * именительный (nominative) — текст в именительном падеже;  
  * родительный (genitive) — текст в родительном падеже;  
  * дательный (dative) — текст в дательном падеже;  
  * винительный (accusative) — текст в винительном падеже;  
  * творительный (instrumental) — текст в творительном падеже;  
  * предложный (prepositional) — текст в предложном падеже;  
  * местный (locative) — текст в местном падеже;]  
  
### Для украинского языка:

Объект ``Morpher\Ws3Client\Ukrainian\СorrectionEntry`` со следующими свойствами:

  * ``singular`` — объект ``Morpher\Ws3Client\Ukrainian\CorrectionForms`` с формами в единственном числе;  

Указание рода не поддерживается.  
  
Объект ``Morpher\Ws3Client\Ukrainian\CorrectionForms`` со следующими свойствами:

  * називний (nominative) — текст в именительном падеже;  
  * родовий (genitive) — текст в родительном падеже;  
  * давальний (dative) — текст в дательном падеже;  
  * знахідний (accusative) — текст в винительном падеже;  
  * орудний (instrumental) — текст в творительном падеже;  
  * місцевий (prepositional) — текст в местном падеже;  
  * кличний (vocative) — текст в звательном падеже.  
  
## Добавить или изменить исправление

Для добавления или изменения исправления использовать метод   
``$morpher->russian->userDict->addOrUpdate($entry)``,  
или аналогично   
``$morpher->ukrainian->userDict->addOrUpdate($entry)``:  
  
    $correctionEntry = new \Morpher\Ws3Client\Russian\CorrectionEntry();  
    $correctionEntry->singular->nominative="чебуратор";  
    $correctionEntry->singular->locative='в чебураторке';  
    $correctionEntry->plural->locative='в чебураториях';  
    $morpher->russian->userDict->addOrUpdate($correctionEntry);  
  
## Удаление исправления

Для того чтобы удалить исправление, достаточно передать строку в именительном падеже в метод   
      
    $morpher->russian->userDict->remove($nominativeForm);     

или аналогично   
      
    $morpher->ukrainian->userDict->remove($nominativeForm);  
  
Пример:  
      
    $morpher->russian->userDict->remove('чебуратор');  
  
# <a name="dev"></a>Разработка

## <a name="devsys"></a>Системные требования
Должны быть установлены:
* PHP 7.4 или выше
* composer
  
## <a name="devinstall"></a>Установка
  
Если в вашем проекте отсутствует файл composer.json,
то необходимо выполнить:

    $ composer init
  
И ответить на вопросы программы (название проекта и т.д.). Обратите внимание на параметр ``minimum-stability`` - он должен быть не выше чем указано в той версии пакета morpher которую вы будете устанавливать в свой проект. Если не получилось установить пакет, попробуйте задать в файле composer.json :    ``"minimum-stability": "dev"``.   

Файл composer.json и папка vendor будут созданы.  
  
Сделайте форк репозитория [morpher-ws3-php-client](https://github.com/morpher-ru/morpher-ws3-php-client).  
Затем выполните:  

    $ git clone https://github.com/<your-github-username>/morpher-ws3-php-client  
    $ cd morpher-ws3-php-client  
    $ composer install  
Должна появиться папка vendor.
  
## Запуск тестов  
  
Запуск юнит теста:

    $ vendor\bin\phpunit  tests\unit  
  
Для запуска интеграционных тестов задать секретный токен, иначе тесты частично будут выполнены с ошибкой.
Есть два способа задать токен:

1)	Подходит для локального запуска. Создать файл ``secret.php`` , в котором объявить константу:  
  
    <?php  
    DEFINE("MORPHER_RU_TOKEN" ,"xxxxx-xxxxxx-xxxxxxx");  
  
2) Подходит для запуска в контейнере GitHub Actions. В GitHub Actions, в разделе Secrets, создать переменную окружения MORPHER_RU_TOKEN, и сохранить токен в неё.  
  
Запуск интеграционного теста:  
  
    $ vendor\bin\phpunit  tests\integration  
  
## Обновление зависимостей  
  
    $ composer update  
  
## Обновление автозагрузки классов composer autoload (после каждого создания нового php файла в проекте)  
  
    $ composer dump-autoload -o  
  
## Выпуск нового релиза

  * Увеличить версию в composer.json.  
  * Добавить новый релиз на Гитхабе.
  * В личном кабинете на https://packagist.org опубликовать пакет.  

## See also   
  
* [doctrine/inflector](https://github.com/doctrine/inflector), a popular pluralization library for English  
* [Mikulas/inflection](https://github.com/Mikulas/inflection), a declension library for the Czech language  
  
