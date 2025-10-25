Тема: Робота з XML даними в PHP

Мета: Ознайомлення з методами побудови розмітки ієрархічно структурованих даних. Набуття навичок використання засобів для побудови та опрацювання XML-структур.

Теоретичні відомості

Наведену нижче частину матеріалу використано з статті: https://www.ibm.com/developerworks/ru/library/x-xmlphp1/.

Вступ

Значення XML в сучасному середовищі розробки додатків важко переоцінити. Тим, хто ніколи не працював з XML в PHP або ще не перейшов на PHP 5, це інструкція допоможе освоїти нову функціональність PHP 5, пов'язану з XML, і оцінити, наскільки простий може бути робота з XML. Саме такими документами оперують, наприклад, Ajax-застосунки при передачі інформації про заповнення форми, в такі ж документи оформляються відповіді API Web-сервісів, таких як weather.com.

Відомості про XML

Мова Extensible Markup Language (XML) можна назвати і мовою розмітки, і форматом зберігання текстових даних. Це підмножина мови Standard Generalized Markup Language (SGML); він надає текстові засоби для опису деревовидних структур і їх застосування до інформації. XML служить основою для цілого ряду мов і форматів, таких як Really Simple Syndication (RSS), Mozilla XML User Interface Language (XUL), Macromedia Maximum eXperience Markup Language (MXML), Microsoft eXtensible Application Markup Language (XAML) і open source-мову Java XML UI Markup Language (XAMJ).

Структура XML

Базовим блоком даних в XML є елемент. Елементи виділяються початковим тегом, таким як <book>, і кінцевим тегом, таким як </ book>.Кожному початкового тегу повинен відповідати кінцевий тег. Якщо для якогось початкового тега відсутній кінцевий тег, XML-документ оформлений неправильно, і синтаксичний аналізатор (парсер) не зможе проаналізувати його належним чином. Назви тегів зазвичай відображають тип елемента. Можна очікувати, що елемент book містить назву книги, наприклад, «Великий американський роман» (див. лістинг 12.1). Текст, що міститься між тегами, включаючи пробіли, називається символьними даними.

Лістинг 12.1 – Приклад XML-документа

<Books>
<Book>
 <Title> Великий американський роман </ title>
 <Characters>
  <Character>
   <Name> Кліфф </ name>
   <Desc> відмінний хлопець </ desc>

</ Character>
  <Character>
   <Name> Миловидна Жінка </ name>
   <Desc> рідкісна красуня </ desc>

</ Character>
  <Character>
   <Name> Відданий Пес </ name>
   <Desc> любить поспати </ desc>

</ Character>

</ Characters>
 <Plot>

Кліфф зустрічає Миловиду Жінку. Відданий Пес спить,

але прокидається, щоб нагавкати листоноші.

</ Plot>

<Success type = "bestseller"> 4 </ success>

<Success type = "bookclubs"> 9 </ success>

</ Book>

</ Books>

Імена XML-елементів та атрибутів можуть містити лише латинські літери верхнього (AZ) і нижнього (az) регістрів, цифр (0-9), деяких спеціальних і неанглійських символів, а також трьох знаків пунктуації: дефіса, знака підкреслення і точки. Інші символи в іменах не допускаються.

XML чутливий до регістру. У наведеному прикладі <Book "і" book> описують два різних елемента. Обидва імені прийнятні. Однак опис двох різних елементів іменами <Book "і" book> не можна вважати розумним рішенням з огляду на високу ймовірність помилок.

Кожен документ XML містить один і тільки один кореневий елемент. Кореневий елемент – це єдиний елемент XML-документа, для якого немає батьківського елементу. У наведеному вище прикладі кореневих є елемент <books>. Більшість XML-документів містять батьківські і дочірні елементи. Елемент <books> має один дочірній елемент <book>. У елемента <book> чотири дочірніх елемента: <title>, <characters "," plot "і" success>. У елемента <characters> три дочірніх елемента, кожен з яких є елементом <character>. У кожного елемента <character> по два дочірніх елемента, <name> і <desc>.

Крім вкладених елементів, що створює відносини батьківський-дочірній, XML-елементи можуть мати атрибути. Це пари ім'я-значення, приєднані до початкового тегу елемента. Імена відокремлюються від значень знаком рівності, =. Значення полягають в одинарні або подвійні лапки. У лістингу 1 елемент <success> має два атрибути: "bestseller" і "bookclubs". XML-розробники практикують різні підходи до використання атрибутів. Велику частину інформації, що міститься в атрибуті, можна помістити в дочірній елемент. Деякі розробники наполягають на тому, щоб інформація атрибутів складалася не з даних, а з метаданих, тобто відомостей про дані. Самі дані повинні міститися в елементах. Насправді рішення про те, чи використовувати атрибути, залежить від природи даних і від того, як вони витягуються з XML.

Переваги XML

Одна з переваг XML полягає в його відносній простоті. XML-документ можна скласти в простому текстовому редакторі або текстовому процесорі, не вдаючись до спеціальних інструментів або ПЗ. Базовий синтаксис XML складається з вкладених елементів, деякі з яких мають атрибути і вміст. Зазвичай елемент починається відкриває тегом <тег> і закінчується відповідним закриває тегом </ тег>. XML чутливий до регістру і не ігнорує пробіли і табуляції. Він дуже схожий на HTML, але, на відміну від HTML, дозволяє привласнювати тегам імена для кращого опису свох даних. До числа переваг XML відноситься самодокументірованіе, читабельний для людей і комп'ютерів формат, підтримка Unicode, що дозволяє створювати документи на різних мовах, і прості вимоги до синтаксису і синтаксичному аналізу.На жаль, в PHP 5 підтримка UTF-8 пов'язана з проблемами; це один з тих недоліків, які призвели до розробки [?]PHP 6[/?].

Недоліки XML

XML надлишковий, що породжує документи великого обсягу, що займають багато дискового простору і мережевих ресурсів. Передбачається, що він повинен бути читабельний для людей, але важко уявити собі людину, що намагається прочитати файл XML з 7 млн. Вузлів. Найпростіші синтаксичні аналізатори функціонально не здатні підтримувати широкий набір типів даних; з цієї причини рідкісні або незвичайні дані, яких буває багато, стають серйозним джерелом труднощів.

Правильно побудований XML

XML-документ вважається побудованим правильно, якщо в ньому дотримані правила XML-синтаксису. Неправильно побудований формат в технічному сенсі не є XML-документом. Наприклад, такий HTML-тег, як <br>, в XML неприйнятний; відповідний правильний тег виглядає як <br />. Кореневий елемент можна уявити собі як нескінченний шафа з документами. У вас всього одна шафа, але майже немає обмежень на тип і кількість його вмісту. У вашій шафі поміщається нескінченну кількість ящиків і папок для документів.

[?]PHP 5[/?] і XML

Підтримка XML була присутня в PHP з найраніших версій, але в [?]РНР 5[/?] вона істотно поліпшено. Підтримка XML в PHP 4 була обмеженою, зокрема, пропонувався тільки включений за замовчуванням парсер на базі SAX, а підтримка DOM не відповідала стандарту W3C. У PHP 5 розробники PHP XML, можна сказати, винайшли колесо, забезпечивши відповідність загальноприйнятим стандартам.

Нове в підтримці XML в версії PHP 5

PHP 5 містить повністю переписані і нові розширення, включаючи парсер SAX, DOM, SimpleXML, XMLReader, XMLWriter і процесор XSLT. Тепер всі ці розширення засновані на libxml2.

Поряд з поліпшеною в порівнянні з PHP 4 підтримкою SAX, в PHP 5 реалізована підтримка DOM відповідно до стандарту W3C, а також розширення SimpleXML. За замовчуванням включені і SAX, і DOM, і SimpleXML. Тим, хто знайомий з DOM по іншим мовам, стане простіше реалізувати аналогічну функціональність в PHP.

Читання, обробка та написання XML в PHP 5

SimpleXML, при необхідності в поєднанні з DOM, – ідеальний вибір для читання, обробки і складання в PHP 5 простих, передбачуваних і щодо компактних документів.

Короткий огляд бажаних API

З безлічі API, присутніх в PHP 5, DOM – самий знайомий, а на SimpleXML найпростіше програмувати. У типових ситуаціях, таких як ті, що ми тут розглядаємо, вони найбільш ефективні.

Розширення DOM. Document Object Model (DOM) – це прийнятий W3C стандартний набір об'єктів для документів HTML і XML, стандартна модель поєднання цих об'єктів і стандартний інтерфейс для доступу до них і маніпуляцій з ними. Багато постачальників підтримують DOM в якості інтерфейсу до своїх спеціальним структурам даних і API, завдяки чому модель DOM знайома масі розробників. DOM легко освоїти і застосовувати, так як його структура в пам'яті нагадує вихідний документ XML. Щоб передати інформацію з додатком, DOM створює дерево об'єктів, яке в точності повторює дерево елементів файлу XML, так що кожен елемент XML служить вузлом цього дерева. DOM – це парсер, заснований на структурі дерева. Оскільки DOM будує дерево всього документа, він споживає багато ресурсів пам'яті і часу процесора. Тому аналіз дуже великих документів за допомогою DOM непрактичний через проблеми продуктивності. В контексті даної статті розширення DOM використовується головним чином через його здатності імпортувати формат SimpleXML і виводити XML в форматі DOM, або, навпаки, для використання в якості строкових даних або XML-файла.

SimpleXML. Розширення SimpleXML – це кращий інструмент для синтаксичного аналізу XML. Для його роботи потрібно [?]РНР 5[/?]. Воно взаємодіє з DOM при складанні XML-файлів і має вбудовану підтримку XPath. SimpleXML найкраще працює з нескладними даними типу записів, такими як XML, який передається у вигляді документа або рядки з іншої частини того ж додатка. Якщо XML-документ не надто складний, не дуже глибокий і не містить змішаного контенту, для SimpleXML кодувати простіше, ніж для DOM, як і випливає з назви. До того ж він надійніший при роботі з відомою структурою документа.

Використання DOM

Модель DOM, реалізована в PHP 5, – це та ж специфікація W3C DOM, з якої ви маєте справу в браузері і з якою працюєте за допомогою JavaScript. Використовуються ті ж методи, так що способи кодування здадуться вам знайомими. Лістинг 12.2 ілюструє використання DOM для створення XML-рядки і XML-документа, відформатованих в цілях читабельності.

Лістинг 12.2 – Застосування DOM

<?Php

// Створює XML-рядок і XML-документ за допомогою DOM

$Dom = new DomDocument ( '1.0');

// Додавання кореня – <books>

$Books = $dom-> appendChild ($dom-> createElement ( 'books'));

// Додавання елемента <book> в <books>

$Book = $books-> appendChild ($dom-> createElement ( 'book'));

// Додавання елемента <title> в <book>

$Title = $book-> appendChild ($dom-> createElement ( 'title'));

// Додавання елемента текстового вузла <title> в <title>

$Title-> appendChild (
               $Dom-> createTextNode ( 'Great American Novel'));

// Генерація xml

$Dom-> formatOutput = true; // Установка атрибуту formatOutput
                          // DomDocument в значення true

// Save XML as string or file

$Test1 = $dom-> saveXML (); // Передача рядка в test1

$Dom-> save ( 'test1.xml'); // Збереження файлу


Це призводить до створення вихідного файлу, наведеного в лістингу 12.3.

Лістинг 12.3 – Вихідний файл
<? Xml version = "1.0"

<Books>
 <Book>
   <Title> Great American Novel </ title>
 </ Book>

</ Books>


Лістинг 12.4 імпортує об'єкт SimpleXMLElementв об'єкт DOMElement, ілюструючи взаємодія DOM і SimpleXML.


Лістинг 12.4 – Взаємодія, частина 1 – DOM імпортує SimpleXML.

<?Php

$Sxe = simplexml_load_string ( '<books> <book> <title>'.
     'Great American Novel </ title> </ book> </ books>');

if ($sxe === false) {
 echo 'Error while parsing the document';
 exit;

}

$Dom_sxe = dom_import_simplexml ($sxe);

if (! $dom_sxe) {
 echo 'Error while converting XML';
 exit;

}

$Dom = new DOMDocument ( '1.0');

$Dom_sxe = $dom-> importNode ($dom_sxe, true);

$Dom_sxe = $dom-> appendChild ($dom_sxe);

echo $dom-> save ( 'test2.xml');


Функція з лістингу 12.5 бере вузол документа DOM і перетворює його в вузол SimpleXML. Потім цей новий об'єкт можна використовувати в якості «рідного» елемента SimpleXML. У разі будь-якої помилки повертається значення FALSE.


Лістинг 12.5 – Взаємодія, частина 2 – SimpleXML імпортує DOM

<?Php

$Dom = new domDocument;

$Dom-> loadXML ( '<books> <book> <title> Great American

Novel </ title> </ book> </ books> ');

if (! $dom) {
  echo 'Error while parsing the document';
  exit;

}

$S = simplexml_import_dom ($dom);

echo $s-> book [0] -> title; // Great American Novel


Використання SimpleXML


Розширення SimpleXML – це кращий інструмент для синтаксичного аналізу XML. Воно взаємодіє з DOM при складанні XML-файлів і має вбудовану підтримку XPath. Для SimpleXML простіше писати код, ніж для DOM, як і передбачає його назва. Для тих, хто не знайомий з [?]РНР[/?], лістинг 12.6 форматує тестовий XML-файл як include-файл з метою читабельності.


Лістинг 12.6 – У наступних прикладах тестовий XML-файл відформатований як include-файл [?]РНР[/?] з ім'ям example.php.

<?Php
$Xmlstr = <<< XML
<Books>
<Book>
 <Title> Great American Novel </ title>
 <Characters>
  <Character>
   <Name> Cliff </ name>
   <Desc> really great guy </ desc>
  </ Character>
  <Character>
   <Name> Lovely Woman </ name>
   <Desc> matchless beauty </ desc>
  </ Character>
  <Character>
   <Name> Loyal Dog </ name>
   <Desc> sleepy </ desc>
  </ Character>
 </ Characters>
 <Plot>
  Cliff meets Lovely Woman. Loyal Dog sleeps, but wakes up to bark
  at mailman.
 </ Plot>
 <Success type = "bestseller"> 4 </ success>
 <Success type = "bookclubs"> 9 </ success>
</ Book>
</ Books>

XML;


У гіпотетичному Ajax-додатку може, наприклад, знадобитися витягти з XML-документа поштовий індекс і звернутися до бази даних. У лістингу 12.7 з XML-файла попереднього прикладу витягується елемент <plot>.


Лістинг 12.7 – Витяг вузла – як легко його отримати?

<?Php
include 'example.php';
$Xml = new SimpleXMLElement ($xmlstr);
echo $xml-> book [0] -> plot; // "Cliff meets Lovely Woman. ..."


З іншого боку, може знадобитися отримати багатостроковий адресу. Коли у одного батьківського елемента кілька примірників дочірнього елемента, застосовується звичайна методика ітерірованія. Ця функціональність показана в лістингу 12.8.


Лістинг 12.8 – Витяг декількох екземплярів елемента

<?Php
include 'example.php';
$Xml = new SimpleXMLElement ($xmlstr);

/* For each <book> node, echo a separate <plot>. */
foreach ($xml-> book as $book) {
  echo $book-> plot, '<br />';
}



Крім читання імен елементів і їх значень, SimpleXML може також звертатися до атрибутів елемента. У лістингу 12.9 виробляється звернення до атрибутів елемента; це робиться точно так же, як звернення до елементів масиву.


Лістинг 12.9 – Демонстрація звернення SimpleXML до атрибутів елемента
<?Php
// Повторюємо вхідний XML-файл повторюється для наочності
$Xmlstr = <<< XML
<? Xml version = '1.0' standalone = 'yes'
<Books>
 <Book>
  <Title> Great American Novel </ title>
  <Characters>
   <Character>
    <Name> Cliff </ name>
    <Desc> really great guy </ desc>
   </ Character>
   <Character>
    <Name> Lovely Woman </ name>
    <Desc> matchless beauty </ desc>
   </ Character>
   <Character>
    <Name> Loyal Dog </ name>
    <Desc> sleepy </ desc>
   </ Character>
  </ Characters>
  <Plot>
   Cliff meets Lovely Woman. Loyal Dog sleeps, but wakes up to bark
   at mailman.
  </ Plot>
  <Success type = "bestseller"> 4 </ success>
  <Success type = "bookclubs"> 9 </ success>
 </ Book>
</ Books>

XML;


<?Php
include 'example.php';
$Xml = new SimpleXMLElement ($xmlstr);

/* Доступ до <success> вузлів першої книги.

* Вивів успішних показників. */
foreach ($xml-> book [0] -> success as $success) {
   switch ((string) $success [ 'type']) {
       // Get attributes as element indices
   case 'bestseller':
       echo $success, 'months on bestseller list';
       break;
   case 'bookclubs':
       echo $success, 'bookclub listings';
       break;
   }
}



Щоб порівняти елемент або атрибут з рядком або передати його функції, якій потрібно рядок, потрібно перетворити його в рядок за допомогою оператора (string). Інакше, за замовчуванням, PHP розглядає елемент як об'єкт (див. лістинг 12.10).


Лістинг 12.10 – Перетвори в рядок, або програєш
<?Php
include 'example.php';
$Xml = new SimpleXMLElement ($xmlstr);
if ((string) $xml-> book-> title == 'Great American Novel') {
   print 'My favorite book.';
}
htmlentities ((string) $xml-> book-> title);



Дані в SimpleXML не зобов'язані бути незмінними. Лістинг 12.11 виводить новий XML-документ, точну копію вихідного, за винятком того, що в новому ім'я Cliff змінено на Big Cliff.


Лістинг 12.11 – Зміна текстового вузла за допомогою SimpleXML

<?Php
$Xmlstr = <<< XML
<? Xml version = '1.0' standalone = 'yes'
<Books>
 <Book>
  <Title> Great American Novel </ title>
  <Characters>
   <Character>
    <Name> Cliff </ name>
    <Desc> really great guy </ desc>
   </ Character>
   <Character>
    <Name> Lovely Woman </ name>
    <Desc> matchless beauty </ desc>
   </ Character>
   <Character>
    <Name> Loyal Dog </ name>
    <Desc> sleepy </ desc>
   </ Character>
  </ Characters>
  <Plot>
   Cliff meets Lovely Woman. Loyal Dog sleeps, but wakes up to bark
   at mailman.
  </ Plot>
  <Success type = "bestseller"> 4 </ success>
  <Success type = "bookclubs"> 9 </ success>
 </ Book>
</ Books>

XML;

<?Php
include 'example.php';
$Xml = new SimpleXMLElement ($xmlstr);
$Xml-> book [0] -> characters-> character [0] -> name = 'Big Cliff';
echo $xml-> asXML ();



Починаючи з версії PHP 5.1.3 SimpleXML доповнений можливістю легко додавати дочірні елементи і атрибути. Лістинг 12.12 виводить XML-документ, заснований на вихідному, але з доданим новим персонажем і описом.


Лістинг 12.12 – Додавання дочірніх і текстових вузлів за допомогою SimpleXML

<?Php
$Xmlstr = <<< XML
<? Xml version = '1.0' standalone = 'yes'
<Books>
 <Book>
  <Title> Great American Novel </ title>
  <Characters>
   <Character>
    <Name> Cliff </ name>
    <Desc> really great guy </ desc>
   </ Character>
   <Character>
    <Name> Lovely Woman </ name>
    <Desc> matchless beauty </ desc>
   </ Character>
   <Character>
    <Name> Loyal Dog </ name>
    <Desc> sleepy </ desc>
   </ Character>
   <Character>
    <Name> Yellow Cat </ name>
    <Desc> aloof </ desc>
   </ Character>
  </ Characters>
  <Plot>
   Cliff meets Lovely Woman. Loyal Dog sleeps, but wakes up to bark
   at mailman.
  </ Plot>
  <Success type = "bestseller"> 4 </ success>
  <Success type = "bookclubs"> 9 </ success>
 </ Book>
</ Books>

XML;
   

<?Php
include 'example.php';
$Xml = new SimpleXMLElement ($xmlstr);

$Character = $xml-> book [0] -> characters-> addChild ( 'character');
$Character-> addChild ( 'name', 'Yellow Cat');
$Character-> addChild ( 'desc', 'aloof');

$Success = $xml-> book [0] -> addChild ( 'success', '2');
$Success-> addAttribute ( 'type', 'reprints');

echo $xml-> asXML ();


Основи XML-парсингу


Наведену нижче частину матеріалу використано з статті: http://www.ibm.com/developerworks/ru/library/x-xmlphp2/#ibm-pcon.


Існує два основних способи XML-парсингу: на базі дерев і на базі потоків. Метод дерева передбачає завантаження в пам'ять за все XML-документа цілком. Деревоподібна структура файлу дозволяє довільно звертатися до елементів документа і редагувати XML. Прикладами парсеров за методом дерева служать DOM і SimpleXML. Вони зберігають деревоподібну структуру в пам'яті в різних, але взаємодіючих форматах. При потоковому парсингу весь документ в пам'ять не завантажується. В даному випадку термін «потік» вживається в тому ж сенсі, що і при описі поточного аудіо. Відбувається те ж саме і з тих же причин: дані надходять дрібними порціями з метою економії смуги пропускання і ресурсів пам'яті. При потоковому парсингу доступний тільки той вузол, який аналізується в даний момент, а редагування XML-документа цілком неможливо. Прикладами поточних парсеров служать XMLReader і SAX.
Парсери, що працюють за методом дерева.

Парсери, що працюють за методом дерева, завантажують в пам'ять весь документ, так що корінь нагадує стовбур дерева, а все дочірні, внучаті і більш віддалені нащадки і атрибути служать гілками. Самий відомий парсер, який працює за методом дерева, це DOM.Найпростіший – SimpleXML. Розглянемо той і інший.
Парсинг за допомогою DOM.

Стандарт DOM, згідно W3C, являє собою «... не залежить від платформи і мови програмування інтерфейс, який дозволяє програмам і сценаріям динамічно звертатися до документів і редагувати їх зміст, структуру і стиль». Бібліотека libxml2 проекту GNOME реалізує DOM разом з усіма його методами на мові [?]С[/?]. Так як все XML-розширення [?]РНР 5[/?] засновані на libxml2, вони підтримують повну взаємодію один з одним. Ця взаємодія значно покращує їх функціональність. Наприклад, можна витягти елемент за допомогою поточного парсеру XMLReader, імпортувати його в DOM і витягти дані з використанням XPath. Підтвердження подібної гнучкості продемонстровано лістингу 12.17.

Парсер DOM працює за методом дерева. Він простий для розуміння і застосування, так як його структура в пам'яті нагадує оригінальний XML-документ. DOM передає інформацію з додатком, створюючи дерево об'єктів, в точності повторює дерево елементів з XML-файла, так що кожен елемент XML служить вузлом цього дерева. DOM – це стандарт W3C, що принесло йому визнання розробників зважаючи на його узгодженості з іншими мовами програмування. Так як DOM будує дерево всього документа, він споживає великий обсяг пам'яті і багато ресурсів процесора.

Використання DOM


Якщо через якогось обмеження ви змушені вибрати єдиний парсер, має сенс вибрати DOM хоча б в силу його гнучкості. DOM дозволяє складати XML-документи, модифікувати їх, звертатися до них, перевіряти і перетворювати їх. При цьому можна використовувати всі методи і властивості DOM.Большінство методів DOM другого рівня реалізовані з належною підтримкою властивостей. Завдяки надзвичайній гнучкості DOM аналізовані документи можуть бути надзвичайно складними. Однак пам'ятайте, що за гнучкість доводиться платити тим, що весь документ завантажується в пам'ять цілком.

У прикладі, наведеному в лістингу 12.13, DOM застосовується для парсингу документа і вилучення елемента за допомогою функції getElementById. Перед посиланням на ідентифікатор документ необхідно перевірити, встановивши validateOnParse=true.Відповідно до стандарту DOM, для цього потрібно DTD, який визначає атрибут ID як тип ID.


Лістинг 12.13 – Використання DOM з простим документом

<?Php

$Doc = new DomDocument;

// Перш ніж посилатися на id, документ потрібно перевірити

$Doc-> validateOnParse = true;

$Doc-> Load ( 'basic.xml');

echo "The element whose id is myelement is:".

$Doc-> getElementById ( 'myelement') -> tagName. "\ N";


Функція getElementsByTagName()повертає новий екземпляр класу DOMNodeList, що містить елементи з заданим ім'ям тега.Звичайно, потрібно перегорнути список. Зміна структури документа під час роботи зі списком NodeList, виданими функцією getElementsByTagName(), впливає на список NodeList, з яким ви працюєте (див. Лістинг 12.14). Перевірка в даному випадку не потрібно.


Лістинг 12.14 – Метод DOM getElementsByTagName

DOMDocument {
DOMNodeList getElementsByTagName (string name);

}


У прикладі з лістингу 12.15 DOM використовується з XPath.


Лістинг 12.15 – Використання DOM і парсинг із застосуванням XPath

<?Php

$Doc = new DOMDocument;

// Ми не хочемо возитися з пробілами

$Doc-> preserveWhiteSpace = false;

$Doc-> Load ( 'book.xml');

$Xpath = new DOMXPath ($doc);

// Ми почали з кореневого елемента

$Query = '// book / chapter / para / informaltable / tgroup / tbody / row / entry [. = "En"] ';

$Entries = $xpath-> query ($query);

foreach ($entries as $entry) {
 echo "Found {$entry-> previousSibling-> previousSibling-> nodeValue},".
      "By {$entry-> previousSibling-> nodeValue} \ n";

}


Висловивши всі ці приємні речі з приводу DOM, я хочу навести приклад того, що не треба робити з DOM, а потім, в наступному прикладі, показати, як це обійти. Лістинг 4 ілюструє завантаження об'ємного файлу в DOM тільки з метою отримання даних з одного атрибута за допомогою DomXpath.


Лістинг 12.16 – Неправильне використання DOM з XPath при роботі з об'ємним XML-документом

<?Php

// Парсинг об'ємного документа за допомогою DOM і DomXpath

// Спочатку створимо новий документ DOM для парсингу

$Dom = new DomDocument ();

// Це великий документ, а нам не потрібно нічого, крім дерева

// Цей величезний документ займає масу пам'яті

$Dom-> load ( "tooBig.XML");

$Xp = new DomXPath ($dom);

$Result = $xp-> query ( "/ blog / entries / entry [@ID = 5225] / title");

print $result-> item (0) -> nodeValue. "\ n";


В останньому прикладі (див. лістинг 12.17) DOM з XPath застосовується точно так же, тільки дані передаються в XMLReader між окремими елементами за раз з використанням методу expand(). При цьому вузол, переданий XMLReader, перетворюється в DOMElement.


Лістинг 12.17 – Правильне використання DOM з XPath при роботі з об'ємним XML-документом

<?Php

// Парсинг великого документа за допомогою XMLReader з Expand – DOM / DOMXpath

$Reader = new XMLReader ();

$Reader-> open ( "tooBig.xml");

while ($reader-> read ()) {
  switch ($reader-> nodeType) {
      case (XMLREADER :: ELEMENT):
      if ($reader-> localName == "entry") {
          if ($reader-> getAttribute ( "ID") == 5225) {
              $Node = $reader-> expand ();
              $Dom = new DomDocument ();
              $N = $dom-> importNode ($node, true);
              $Dom-> appendChild ($n);
              $Xp = new DomXpath ($dom);
              $Res = $xp-> query ( "/ entry / title");
              echo $res-> item (0) -> nodeValue;
          }
      }
  }

}


Парсинг за допомогою SimpleXML


Розширення SimpleXML – ще один спосіб парсингу XML-документа. Для розширення SimpleXML потрібно PHP 5 і використовується вбудована підтримка XPath. SimpleXML найкраще працює з нескладними даними XML. У тому випадку, якщо XML-документ не надто складний, глибокий і не має змішаного контенту, SimpleXML простіше в застосуванні, ніж DOM, як і передбачає його назва. Він інтуїтивно зрозумілий, якщо ви працюєте з відомою структурою документа.

Використання SimpleXML


SimpleXML володіє багатьма перевагами DOM і простіше в програмуванні. Він дозволяє легко звертатися до дерева XML, має вбудовану підтримку перевірки і XPath, а також взаємодіє з DOM, забезпечуючи йому підтримку при читанні і запису XML-документів. Документи, аналізовані SimpleXML, пишуться легко і швидко. Однак пам'ятайте, що, як і в разі DOM, простота і гнучкість SimpleXML досягається ціною завантаження в пам'ять за все XML-документа цілком.

Код, наведений у лістингу 12.18, витягує з прикладу XML-документа сюжет твору, що міститься в елементі <plot>.


Лістинг 12.18 – Витяг фрагмента тексту

<?Php

$Xmlstr = <<< XML

<? Xml version = '1.0' standalone = 'yes'

<Books>
 <Book>
    <Title> Great American Novel </ title>
    <Plot>
       Cliff meets Lovely Woman. Loyal Dog sleeps, but
       wakes up to bark at mailman.
    </ Plot>
    <Success type = "bestseller"> 4 </ success>
    <Success type = "bookclubs"> 9 </ success>
 </ Book>

</ Books>

XML;

<?Php

$Xml = new SimpleXMLElement ($xmlstr);

echo $xml-> book [0] -> plot; // "Cliff meets Lovely Woman. ..."


З іншого боку, може знадобитися отримати багатостроковий адресу. Коли у одного батьківського елемента є кілька примірників дочірнього елемента, застосовується звичайна методика ітерірованія. Ця функціональність демонструється в лістингу 12.19.


Лістинг 12.19 – Витяг декількох екземплярів елемента

<?Php

$Xmlstr = <<< XML

<Xml version = '1.0' standalone = 'yes'

<Books>
 <Book>
    <Title> Great American Novel </ title>
    <Plot>
       Cliff meets Lovely Woman.
    </ Plot>
    <Success type = "bestseller"> 4 </ success>
    <Success type = "bookclubs"> 9 </ success>
 </ Book>
 <Book>
    <Title> Man Bites Dog </ title>
    <Plot>
       Reporter invents a prize-winning story.
    </ Plot>
    <Success type = "bestseller"> 22 </ success>
    <Success type = "bookclubs"> 3 </ success>
 </ Book>

</ Books>

XML;

<php

$Xml = new SimpleXMLElement ($xmlstr);

foreach ($xml-> book as $book) {
 echo $book-> plot, '<br />';

}

?


Крім читання імен елементів і їх значень, SimpleXML може звертатися до атрибутів елемента. У лістингу 12.20 виробляється звернення до атрибутів елемента; це робиться точно так же, як звернення до елементів масиву.


Лістинг 12.20 – Демонстрація звернення SimpleXML до атрибутів елемента

<?Php

$Xmlstr = <<< XML

<? Xml version = '1.0' standalone = 'yes'

<Books>
 <Book>
    <Title> Great American Novel </ title>
    <Plot>
       Cliff meets Lovely Woman.
    </ Plot>
    <Success type = "bestseller"> 4 </ success>
    <Success type = "bookclubs"> 9 </ success>
 </ Book>
 <Book>
    <Title> Man Bites Dog </ title>
    <Plot>
       Reporter invents a prize-winning story.
    <Plot>
    <Success type = "bestseller"> 22 </ success>
    <Success type = "bookclubs"> 3 </ success>
 </ Book>

<Books>

XML;

<?Php

$Xml = new SimpleXMLElement ($xmlstr);

foreach ($xml-> book [0] -> success as $success) {
 switch ((string) $success [ 'type']) {
 case 'bestseller':
    echo $success, 'months on bestseller list <br />';
    break;
 case 'bookclubs':
    echo $success, 'bookclub listings <br />';
    break;
 }

}


В останньому прикладі (див. лістинг 12.21) SimpleXML і DOM використовуються з розширенням XMLReader. За допомогою XMLReaderдані передаються послідовно, між окремими елементами з використанням методу expand(). Цим методом вузол, переданий XMLReader, можна перетворити в DOMElement, а потім передати SimpleXML.


Лістинг 12.21 – Використання SimpleXML і DOM з розширенням XMLReader для аналізу об'ємного XML-документа

<?Php

// Парсинг великого документа за допомогою Expand і SimpleXML

$Reader = new XMLReader ();

$Reader-> open ( "tooBig.xml");

while ($reader-> read ()) {
  switch ($reader-> nodeType) {
      case (XMLREADER :: ELEMENT):
      if ($reader-> localName == "entry") {
          if ($reader-> getAttribute ( "ID") == 5225) {
              $Node = $reader-> expand ();
              $Dom = new DomDocument ();
              $N = $dom-> importNode ($node, true);
              $Dom-> appendChild ($n);
              $Sxe = simplexml_import_dom ($n);
              echo $sxe-> title;
          }
      }
  }

}

Потокові парсери

Потокові парсери називаються так тому, що вони аналізують XML в потоці, багато в чому нагадуючи роботу потокового аудіо. У кожен момент часу вони працюють з одним окремим вузлом, а закінчивши, зовсім забувають про його існування. XMLReader – це pull-парсер, і програмування для нього багато в чому нагадує витяг результату запиту до таблиці бази даних за допомогою курсору. Це полегшує роботу з незнайомими або непередбачуваними XML-файлами.

Парсинг за допомогою XMLReader


XMLReader – це потоковий парсер того типу, який часто називають курсорними або pull-парсером. XMLReader витягує інформацію з XML-документа на вимогу. Він заснований на API, отриманому з C# XmlTextReader. У PHP 5.1 він включений і задіяний за замовчуванням і заснований на бібліотеці libxml2. До виходу PHP 5.1 розширення XMLReader не було включено за замовчуванням, але було доступно в PECL. XMLReader підтримує простору імен і перевірку, включаючи DTD і Relaxed NG.

Використання XMLReader


Як потоковий парсер, XMLReader добре підходить для роботи з об'ємними XML-документами; програмувати в ньому набагато легше і зазвичай швидше, ніж в SAX. Це кращий потоковий парсер.

У наступному прикладі (див. лістинг 12.22) об'ємний XML-документ аналізується за допомогою XMLReader.


Лістинг 12.22 – XMLReader з об'ємним XML-файлом

<?Php


$Reader = new XMLReader ();

$Reader-> open ( "tooBig.xml");

while ($reader-> read ()) {
 switch ($reader-> nodeType) {
 case (XMLREADER :: ELEMENT):
    if ($reader-> localName == "entry") {
       if ($reader-> getAttribute ( "ID") == 5225) {
          while ($reader-> read ()) {
             if ($reader-> nodeType == XMLREADER :: ELEMENT) {
                if ($reader-> localName == "title") {
                   $Reader-> read ();
                   echo $reader-> value;
                   break;
                }
                if ($reader-> localName == "entry") {
                   break;
                }
             }
          }
       }
    }
 }

}

Парсинг за допомогою SAX


Simple API for XML (SAX) являє собою потоковий парсер. Події пов'язані з читаним XML-документом, тому SAX програмується в стилі зворотних викликів. Існують події для відкривають і закривають тегів елемента, сутностей і помилок парсингу. Головна причина використання парсера SAX замість XMLReader полягає в тому, що парсер SAX іноді ефективніший і зазвичай краще знаком. Важливий недолік – код для парсеру SAX виходить складніше, і його важче писати, ніж для XMLReader.

Використання SAX


SAX повинен бути знаком тим, хто працював з XML в PHP 4, а розширення SAX в PHP 5 сумісно з версією, до якої вони звикли. Так як це потоковий парсер, він добре справляється з об'ємними файлами, але це не кращий вибір, ніж XMLReader.

У лістингу 12.23 наведено приклад обробки об'ємного XML-документа парсером SAX.


Лістинг 12.23 – Використання SAX для аналізу об'ємного XML-файла

<?Php

// Цей клас містить всі методи зворотного виклику,

// Які автоматично керують даними XML.

class SaxClass {
 private $hit = false;
 private $titleHit = false;
 // Зворотний виклик для початку кожного елементу
 function startElement ($parser_object, $elementname, $attribute) {
    if ($elementname == "entry") {
       if ($attribute [ 'ID'] == 5225) {
          $This-> hit = true;
       } Else {
          $This-> hit = false;
       }
    }
    if ($this-> hit && $elementname == "title") {
       $This-> titleHit = true;
    } Else {
       $This-> titleHit = false;
    }
 }
 // Зворотний виклик для кінця кожного елемента
 function endElement ($parser_object, $elementname) {
 }


// Зворотний виклик для вмісту кожного елемента
 function contentHandler ($parser_object, $data)
 {
    if ($this-> titleHit) {
       echo trim ($data). "<br />";
    }
 }

}

// Функція запуску парсингу, коли всі значення встановлені

// І файл відкритий

function doParse ($parser_object) {
 if (! ($fp = fopen ( "tooBig.xml", "r")));

 // Прокрутка даних
 while ($data = fread ($fp, 4096)) {
    // Аналіз фрагмента
    xml_parse ($parser_object, $data, feof ($fp));
 }

}

$SaxObject = new SaxClass ();

$Parser_object = xml_parser_create ();

xml_set_object ($parser_object, $SaxObject);

// Не міняйте регістр даних

xml_parser_set_option ($parser_object, XML_OPTION_CASE_FOLDING, false);

xml_set_element_handler ($parser_object, "startElement", "endElement");

xml_set_character_data_handler ($parser_object, "contentHandler");

doParse ($parser_object);


Завдання


Для бази даних розробленої в лабораторній роботі №21 розробити web-застосунок для вивантаження всіх записів БД в окремий XML-файл.

Розробити web-застосунок для парсингу вивантаженого XML-файлу та виводу даних в браузер у вигляді структур реалізованих табличній формі з використанням блокової верстки та CSS-властивостей display:table; display:table-row; display:table-cell;

У всіх структурних елементах web-застосунку засобами PHP додатково вивести інформацію про розробника (група, прізвище, ім’я, по батькові), дату створення документу, поточну дату. Web-застосунок зберегти та розмістити в окремому каталозі на хостингу, зареєстрованому в лабораторній роботі №2 з попереднього семестру.
