<?php
$group = "Група: СП-41";
$developer = "Розробник: Бармак Роман Миколайович";
$created = "Дата створення: 25.10.2025";
$now = date("d.m.Y H:i");

// variant 4
$variant = 4;
$a = 7;
$b = 14;
$c = 31;
$min_abc = min($a, $b, $c);
$x_values = array(4, 7, 10, 13, 16, 19, 22, 25, 28, 31);
$formula = "y = a * b * c + sqrt(x) + (e^x) + tg(min(a, b, c))";

// style variables
$grid_style = "display: flex; flex-wrap: wrap;";
$container_style = "padding: 12px; display: flex; flex-direction: column; align-items: center; min-height: 90px; margin: 5px; border: 1px solid #ccc;";
$text_style = "text-align: center; margin-top: 5px;";

// display formula
echo "<h1>Формула</h1>";
echo "<p>$formula</p>";

// while
echo "<h2>While</h2>";
echo "<p>Цей розділ використовує стандартний цикл while для табулювання функції y для кожного значення x, відображаючи результати в блоках зі збільшеними розмірами та кольорами.</p>";
echo "<div style='$grid_style'>";
$i = 0;
$h = $variant * 3;
$w = $variant * 5;
$r = 0;
$g = 0;
$bl = 0;
$br = 4;
$bg = 4;
$bb = 4;
while ($i < count($x_values)) {
    $x = $x_values[$i];
    $y = $a * $b * $c + sqrt($x) + exp($x) + tan($min_abc);
    $square_style = "height: {$h}px; width: {$w}px; background-color: rgb({$r}, {$g}, {$bl}); border: 1px solid rgb({$br}, {$bg}, {$bb});";
    echo "<div style=\"$container_style\"><div style=\"$square_style\"></div><div style=\"$text_style\">x = $x<br>y = $y</div></div>";
    $i++;
    $h += 5;
    $w += 10;
    $r += 7;
    $g += 7;
    $bl += 7;
    $br += 7;
    $bg += 7;
    $bb += 7;
}
echo "</div>";
echo "<br><br>";

// while alternative
echo "<h2>While alternative</h2>";
echo "<p>Цей розділ використовує альтернативний синтаксис циклу while з двокрапками та endwhile для виконання тієї ж табуляції.</p>";
echo "<div style='$grid_style'>";
$i = 0;
$h = $variant * 3;
$w = $variant * 5;
$r = 0;
$g = 0;
$bl = 0;
$br = 4;
$bg = 4;
$bb = 4;
while ($i < count($x_values)):
    $x = $x_values[$i];
    $y = $a * $b * $c + sqrt($x) + exp($x) + tan($min_abc);
    $square_style = "height: {$h}px; width: {$w}px; background-color: rgb({$r}, {$g}, {$bl}); border: 1px solid rgb({$br}, {$bg}, {$bb});";
    echo "<div style=\"$container_style\"><div style=\"$square_style\"></div><div style=\"$text_style\">x = $x<br>y = $y</div></div>";
    $i++;
    $h += 5;
    $w += 10;
    $r += 7;
    $g += 7;
    $bl += 7;
    $br += 7;
    $bg += 7;
    $bb += 7;
endwhile;
echo "</div>";
echo "<br><br>";

// do-while
echo "<h2>Do-while</h2>";
echo "<p>Цей розділ використовує цикл do-while, який виконується принаймні один раз, для табулювання функції y.</p>";
echo "<div style='$grid_style'>";
$i = 0;
$h = $variant * 3;
$w = $variant * 5;
$r = 0;
$g = 0;
$bl = 0;
$br = 4;
$bg = 4;
$bb = 4;
do {
    $x = $x_values[$i];
    $y = $a * $b * $c + sqrt($x) + exp($x) + tan($min_abc);
    $square_style = "height: {$h}px; width: {$w}px; background-color: rgb({$r}, {$g}, {$bl}); border: 1px solid rgb({$br}, {$bg}, {$bb});";
    echo "<div style=\"$container_style\"><div style=\"$square_style\"></div><div style=\"$text_style\">x = $x<br>y = $y</div></div>";
    $i++;
    $h += 5;
    $w += 10;
    $r += 7;
    $g += 7;
    $bl += 7;
    $br += 7;
    $bg += 7;
    $bb += 7;
} while ($i < count($x_values));
echo "</div>";
echo "<br><br>";

// for
echo "<h2>For</h2>";
echo "<p>Цей розділ використовує стандартний цикл for для ітерації та відображення табулюваних значень.</p>";
echo "<div style='$grid_style'>";
$h = $variant * 3;
$w = $variant * 5;
$r = 0;
$g = 0;
$bl = 0;
$br = 4;
$bg = 4;
$bb = 4;
for ($i = 0; $i < count($x_values); $i++) {
    $x = $x_values[$i];
    $y = $a * $b * $c + sqrt($x) + exp($x) + tan($min_abc);
    $square_style = "height: {$h}px; width: {$w}px; background-color: rgb({$r}, {$g}, {$bl}); border: 1px solid rgb({$br}, {$bg}, {$bb});";
    echo "<div style=\"$container_style\"><div style=\"$square_style\"></div><div style=\"$text_style\">x = $x<br>y = $y</div></div>";
    $h += 5;
    $w += 10;
    $r += 7;
    $g += 7;
    $bl += 7;
    $br += 7;
    $bg += 7;
    $bb += 7;
}
echo "</div>";
echo "<br><br>";

// for alternative
echo "<h2>For alternative</h2>";
echo "<p>Цей розділ використовує альтернативний синтаксис циклу for з двокрапками та endfor для табуляції.</p>";
echo "<div style='$grid_style'>";
$h = $variant * 3;
$w = $variant * 5;
$r = 0;
$g = 0;
$bl = 0;
$br = 4;
$bg = 4;
$bb = 4;
for ($i = 0; $i < count($x_values); $i++):
    $x = $x_values[$i];
    $y = $a * $b * $c + sqrt($x) + exp($x) + tan($min_abc);
    $square_style = "height: {$h}px; width: {$w}px; background-color: rgb({$r}, {$g}, {$bl}); border: 1px solid rgb({$br}, {$bg}, {$bb});";
    echo "<div style=\"$container_style\"><div style=\"$square_style\"></div><div style=\"$text_style\">x = $x<br>y = $y</div></div>";
    $h += 5;
    $w += 10;
    $r += 7;
    $g += 7;
    $bl += 7;
    $br += 7;
    $bg += 7;
    $bb += 7;
endfor;
echo "</div>";
echo "<br><br>";

// developer info
echo "<p>$group</p>";
echo "<p>$developer</p>";
echo "<p>$created</p>";
echo "<p>Поточна дата: $now</p>";
?></content>