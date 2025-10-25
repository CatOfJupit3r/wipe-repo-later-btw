Тема: Організація AJAX обміну даними (HTML, JavaScript, JSON, PHP).

Мета: Ознайомлення з методами організації інтерактивних сторінок засобами AJAX та jQuery. Набуття навичок використання асинхронного та синхронного обміну даними між веб-застосунками.

Теоретичні відомості

Організація інтерактивної сторінки; комплекс jQuery, AJAX, PHP, MySQLi

Приклад використання AJAX -запиту до файлу із розширенням .php

AJAX PHP Example (URL: http://www.w3schools.com/ajax/tryit.asp?filename=tryajax_suggest_php).

Вищевказаний приклад демонструє, як вебсторінка взаємодіє із сервером, коли користувач вводить символ в пошуковому полі. В прикладі, як лиш користувач ввів символ в поле, виконується функція "showHint()". Функція активується подією onkeyup. HTML:

<html>

<head>

<script>

function showHint(str) {

    if (str.length == 0) {

        document.getElementById("txtHint").innerHTML = "";

        return;

    } else {

        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {

            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

 

                document.getElementById("txtHint").innerHTML = xmlhttp.responseText;

            }

        }

        xmlhttp.open("GET", "gethint.php?q=" + str, true);

        xmlhttp.send();

    }

}

</script>

</head>

<body>

<p><b>Start typing a name in the input field below:</b></p>

<form>

First name: <input type="text" onkeyup="showHint(this.value)">

</form>

<p>Suggestions: <span id="txtHint"></span></p>

</body>

</html>

Пояснення: По-перше, перевіряється чи пошукове поле не пусте (str.length == 0). Якщо пусте, очищаємо контент txtHint та виходимо із функції. Якщо ж поле не пусте, то виконуються наступні операції:

· Створюється об'єкт XMLHttpRequest.

· Створюється функція, що виконається, коли буде готова відповідь сервера.

· Посилається запит до PHP файлу (gethint.php).

· Зазначу, що параметр q додається до gethint.php?q="+str.

· Змінна str має значення контенту, що був введений в пошуковому полі.

Файл PHP – "gethint.php". Файл PHP перевіряє відповідність в масиві імен та повертає відповідне до браузеру:

// Array with names

$a = [

"Anna",

"Brittany",

"Cinderella",

"Diana",

"Eva",

"Fiona",

"Gunda",

"Hege",

"Inga",

"Johanna",

"Kitty",

"Linda",

"Nina",

"Ophelia",

"Petunia",

"Amanda"

]

// get the q parameter from URL

$q = $\_REQUEST["q"];

$hint = "";

// lookup all hints from array if $q is different from ""

if ($q !== "") {

    $q = strtolower($q);

    $len=strlen($q);

    foreach($a as $name) {

        if (stristr($q, substr($name, 0, $len))) {

            if ($hint === "") {

                $hint = $name;

            } else {

                $hint .= ", $name";

            }

        }

    }

}

// Output "no suggestion" if no hint was found or output correct values

echo $hint === "" ? "no suggestion" : $hint;

1.3 Декілька слів про AJAX запит із застосуванням бібліотеки jQuery

Як працює AJAX із jQuery?

Бібліотека jQuery застосовує декілька методів щодо AJAX-запиту. Із AJAX-методами jQuery Ви можете виконати запит даних text, HTML, XML, чи JSON, застосовуючи протокол передачі даних HTTP Get чи HTTP Post. Також маєте змогу завантажити зовнішні дані у вибрану область Вашої веб-сторінки. Створення коду AJAX-запиту може бути дещо складним, так як деякі браузери по різному сприймають запити AJAX. Створення коду для кросбраузерності приводить до написання більшої кількості кодів скрипту. Тим не менш, розробники jQuery потурбувались, щоб максимально зменшити розмір AJAX-запиту.

Метод jQuery – AJAX load()

Метод jQuery – AJAX load() досить простий і разом з тим досить функціональний. Метод load() завантажує дані із сервера та розміщує дані у вибрану область веб-сторінки. Синтаксис:

$(selector).load(URL,data,callback);

Обов'язковий URL параметр вказує URL -адресу, що бажаєте завантажити.

Необов'язковий параметр data вказує дані в парі ключ/значення, що відправляються із запитом до сервера.

Необов'язковий зворотній параметр callback, що містить функцію, яка має виконуватись по завершенню виконання методу load().

Зворотня функція також може мати різні параметри:

· responseTxt – утримує результуючий контент, якщо запит успішний;

· statusTxt – утримує статус запиту;

· xhr – утримує об'єкт XMLHttpRequest.

Наступний приклад показує повідомлення після завершення виконання методу load(). Якщо метод load() був виконаний успішно, виводиться повідомлення "External content loaded successfully!", якщо ж ні, то виводиться повідомлення про помилку:

$("button").click(function(){

    $("#div1").load("demo_test.txt", function(responseTxt, statusTxt, xhr){

        if(statusTxt == "success")

            alert("External content loaded successfully!");

        if(statusTxt == "error")

            alert("Error: " + xhr.status + ": " + xhr.statusText);

    });

});

Методи AJAX get() та post()

Методи jQuery get() та post() застосовуються для зипиту до даних сервера застосовуючи протокол HTTP GET або [/?]POST[/?].

HTTP Запит: GET vs. POST

Два найбільш вживаних запити між клієнтом та сервером це: GET та POST.

· GET – Запрошує дані з зазначеного ресурсу;

· POST – Представляє дані, що підлягають обробці в зазначеному ресурсі.

GET в основному використовується для того щоб отримати деякі дані із сервера.

Примітка: Метод GET кешує дані.

POST також застосовується, щоб отримати дані із сервера. Тим не менш, POST ніколи не кешує дані, і часто використовується, щоб відправити дані до сервера.Детальніше розглянути методи GET та POST, дізнатись, яка різниця між даними методами можна перейшовши за URL: http://www.w3schools.com/tags/ref_httpmethods.asp.

Метод jQuery $.post()

Метод $.post() отримує дані із сервера застосовуючи HTTP POST запитt. Синтаксис:

$.post(URL,data,callback);

Обов'язковий URL параметр, вказує адресу до якого відправляється запит. Необов'язковий параметр data вказує дані, що відправляються із запитом до сервера. Необов'язковий параметр callback вказує функцію, що має виконуватись після завершення запиту. Наступний приклад застосовує метод $.post(), щоб відправити дані до сервера (URL: http://www.w3schools.com/jquery/tryit.asp?filename=tryjquery_ajax_post):

$("button").click(function(){

    $.post("demo_test_post.asp",

    {

        name: "Donald Duck",

        city: "Duckburg"

    },

    function(data, status){

        alert("Data: " + data + "\nStatus: " + status);

    });

});

Перший параметр методу $.post() є URL, до якого відправляється запит ("demo_test_post.asp"). Потім ми передаємо дані разом із запитом (імя та місто). ASP скрипт "demo_test_post.asp" читає параметри, обробляє та повертає результат. Третє, зворотня функція. Перший параметр зворотньої функції утримує контент запрошеної сторінки, та статус запиту.

1.4 w3schools методи jQuery AJAX

В таблиці 11.5 розглянуті AJAX-методи jQuery.

Таблиця 11.5 – AJAX-методи jQuery

Метод

Опис

$.ajax()

Виконує асинхронний AJAX запит.

$.ajaxPrefilter()

Налаштовує опції AJAX-запиту чи модифікує існуючі опції кожного запиту до того, як запит буде відправлений чи виконаний в методі $.ajax().

$.ajaxSetup()

Встановлює значення запиту за замовчуванням.

$.ajaxTransport()

Створює обєкт, що обробляє дані запиту, що пересилаються до сервера.

$.get()

Завантажує дані із сервера, застосовуючи протокол передачі даних HTTP GET.

$.getJSON()

Завантажує JSON дані із сервера застосовуючи HTTP запит.

$.getScript()

Завантажити (та виконати) JavaScript із сервера застосовуючи AJAX HTTP GET запит.

$.param()

Створює упорядковане представлення масиву або об'єкта.

$.post()

Завантажує дані із сервера застосовуючи протокол передачі даних HTTP POST.

ajaxComplete()

Визначає функцію, що має виконуватись після завершення AJAX запиту.

ajaxError()

Визначає функцію, що має виконатись, якщо AJAX-запит помилковий.

ajaxSend()

Визначає функцію, що має виконатись перед AJAX-запитом.

ajaxStart()

Визначає функцію, що має виконатись, як лиш розпочався AJAX-запит.

ajaxStop()

Визначає функцію, що виконується, коли всі AJAX-запити виконані.

ajaxSuccess()

Визначає функцію, що виконується, за умови успішного виконання AJAX-запиту.

load()

Завантажує дані із сервера та поміщає дані у вказану область веб-сторінки.

serialize()

Кодує набір елементів форми у вигляді рядка.

serializeArray()

Кодує набір елементів форми як масив імен та значень.

1.5 Приклад обміну даними з сервером

Організація AJAX-запиту до сервера та обробка даних.

Метод jQuery load() завантажує у вибрану область selector дані AJAX запиту, синтаксис методу:

$(selector).load(url,data,function(response,status,xhr))

В таблиці 11.6 описані властивості методу jQuery load().

Таблиця 11.6 – Властивості методу jQuery load()

Параметр

Опис

URL

Обов'язковий. Вказує URL-адресу, до якої звертається AJAX-запит.

data

Необов'язковий. Вказуються дані, що відправляються до сервера

function(response,status,

xhr)

Необов'язковий. Функція, що виконується по завершенню метода load().

Додаткові параметри:

· response – складається із результуючих даних, що повернулись із сервера

· status – має значення статусу запиту ("success", "notmodified", "error", "timeout", or "parsererror")

· xhr – об'єкт XMLHttpRequest

Розглянемо дещо детальніше метод load() на прикладі Демо-сторінки Досліджуємо AJAX метод load(). Приклад 1 розміщеної за URL: http://bestwebit.biz.ua/pages_03/demo_ajax_load_data.php. Ще раз синтаксис методу:

$(selector).load(URL,data,callback);

де, selector – область, куди завантажуємо результат AJAX запиту,

      URL – адреса, до якої відправляється AJAX-запит,

      data – дані що відправляємо, до вказаної URL-адреси,

      callback – функція, що виконується по завершенню AJAX-запиту

В методі load() обов'язковими полями є лиш URL – адреса, все інше необов'язкове і застосовується за потреби веб-програміста.

Приклад використання методу load() із Демо-сторінки:

$(document).ready(function(){

$( ".trigger" ).click(function() {

var data_01 = $("#data_01").prop("checked");

var data_02 = $("#data_02").prop("checked");

$(".result_01").load("demo_ajax_load_data.php#test",

              {data_01,data_02},

                     function(responseTxt,status,xhr){

                           $("#result_02_1" ).text(responseTxt);

                           $("#result_02_2" ).text(status);

                           $("#result_02_3" ).text(xhr);

                        });

     });

});

Розглянемо ключові моменти даного методу.

1. Як відправити дані AJAX-запиту на сервер за допомогою AJAX метода load()?

Синтаксис AJAX-у стверджує, що параметр вказується або самостійно або в парі ім'я/ключ:

$.AJAX(parameter);

або

$.ajax({name:value, name:value, ... });

Відповідно, дані на сервер можемо відправити, наприклад, таким чином:

$(".result").load("demo_ajax_load_data.php",{data_01,data_02});

Чи наприклад, ось так, Досліджуємо AJAX метод load(). Приклад 2 розміщений за URL: http://bestwebit.biz.ua/pages_03/demo_ajax_load_data_02.php :

var txt1 = $("#input_msg1").val();

var txt2 = $("#input_msg2").val();

$(".result").load("demo_ajax_load_data.php", {data_01:txt1,data_02:txt2});

2. Як отримати дані із сервера?

Ну по-перше, результуючі дані ми отримуємо із сервера в області selector; Наведений вище приклад 1 – це область result. Саме в цій області виводиться результат виконання файлу за вказаною URL-адресою на сервері. Як зразок виконання php-обробника AJAX-запиту:

echo 'Data 1 is '.$\_POST['data_01'];

echo 'Data 2 is '.$\_POST['data_02'];

Дані із сервера є також в callback – функції, розглянутій нижче.

3. Що ж повертає callback – функція AJAX методу load()?

Зворотню функцію callback можна дослідити на прикладі наведеному за URL: http://bestwebit.biz.ua/pages_03/demo_ajax_load_data.php. Виводимо дані сервера на веб-сторінці:

<div class="result_02">

response:

<span id="result_02_1">:)</span>

status:

<span id="result_02_2">>:)</span>

xhr:

<span id="result_02_3">:)</span>

</div>

Виконання AJAX методу load():

$(document).ready(function(){

$( ".trigger" ).click(function() {

var data_01 = $("#data_01").prop("checked");

var data_02 = $("#data_02").prop("checked");

$(".result_01").load("demo_ajax_load_data.php",

              {data_01,data_02},

                     function(responseTxt,status,xhr){

                           $("#result_02_1" ).text(responseTxt);

                           $("#result_02_2" ).text(status);

                           $("#result_02_3" ).text(xhr);

                        });

     });

});

Скрипт jQuery блок 2 відслідковує події веб-сторінки блок 1 та відправляє ассинхронний запит до PHP-обробника блок 3. У випадку реалізації веб-сторінки Quiz "English word", скрипт jQuery відслідковує чи користувач активував кнопки "Старт тесту", "Коректний переклад пари слова" , "Некоректний переклад пари слова", а також час проведення тесту, якщо час тестування вийшов, то формується AJAX-запит щодо закінчення тестування (див. рис. 11.5). Зразок формування AJAX- запиту до PHP-скрипту:

<script>

$( document ).ready(function() {

     

  $( "#right_word" ).click(function() {    

    var quiz='correct';                

    $( ".area_for_server_answer" ).load("english_quiz.php",{quiz});     

  });

      

  $( "#wrong_word" ).click(function() {    

    var quiz='incorrect';              

    $( ".area_for_server_answer" ).load("english_quiz.php",{quiz});     

   });

</script>

PHP скрипт

PHP – серверна мова програмування, виконує за алгоритмом та обробку отриманих даних, взаємодіє із сервером Бази Даних та формує новий елемент області HTML сторінки. Якщо ж ще раз переглянути схему на рис. 11.5 в частині PHP, то PHP-скрипт отримавши запит від клієнта виконує дії згідно прописаного алгоритму. Так, PHP-скрипт може обробляти дані результуючого запиту до сервера БД, наприклад за таким зразком:

...

// отримуємо дані AJAX запиту

$quiz=$\_POST['quiz'];

//формуємо першу пару слів

if ($quiz=="start") {

// зєднуємось із БД

$mysqli = new mysqli( дані доступу до БД );

mysqli_set_charset($mysqli, "utf8");

//отримуємо дані таблиці

$res = $mysqli->query("SELECT \*

         FROM `english_word`

         ORDER BY RAND()

         LIMIT 1;");

$row = $res->fetch_assoc();

//виводимо дані таблиці english_word

echo ' Слово '.$row['what_word'];

// закриваємо зєднання із БД

$mysqli->close();

};

Звісно, все що виводить PHP-скрипт echo .... виводиться в області selector, вказаному в AJAX методі load():

$(selector).load(URL,data,callback);

Відповідно, можемо реалізувати іншу обробку отриманих даних від клієнта, на зразок наступної частини PHP-скрипту, в якій в залежності від отриманих даних, виконується обробка функцій Check_Word(), Choose_Word(), Show_Word():

...

if ( $quiz == "correct" ) {

Check_Word(1);

Choose_Word ();

Show_Word($what_word,$quiz_word);

};

//блок що формується, якщо юзер неправильно відповів

if ( $quiz == "incorrect" ) {

Check_Word(0);

Choose_Word ();

Show_Word($what_word,$quiz_word);

};

Завдання

Переробити web-застосунок розроблений в лабораторній роботі #6 для реалізації функцій БД з використанням AJAX обміну даними. У web-застосунку частину функціональних можливостей реалізувати з використанням об'єктів XMLHttpRequest. Решту функціональних можливостей реалізувати з використанням бібліотеки jQuery. У всіх структурних елементах web-застосунку засобами PHP додатково вивести інформацію про розробника (група, прізвище, ім’я, по батькові), дату створення документу, поточну дату. Web-застосунок зберегти та розмістити в окремому каталозі на хостингу, зареєстрованому в лабораторній роботі №2 з попереднього семестру.
