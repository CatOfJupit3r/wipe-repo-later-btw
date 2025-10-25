<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Обробка масивів PHP - Варіант 4</title>
    <style>
        body { font-family: sans-serif; margin: 20px; font-size: 16px; }
        .block { padding: 10px; margin-bottom: 10px; }
        .formula { padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
<?php
$group = "Група: СП-41";
$developer = "Розробник: Бармак Роман Миколайович";
$created = "Дата створення: 25.10.2025";
$now = date("d.m.Y H:i");

// Вхідний масив D
$D = array(6, 8, 12, -5, 4, 3, -8, 1, 8, 5);

// Обчислення добутку елементів масиву D
$product = 1;
foreach ($D as $value) {
    $product *= $value;
}

// Визначення кореня
if ($product > 0) {
    $root = pow($product, 1/2);
    $root_type = 'квадратний';
} else {
    $root = pow($product, 1/3);
    $root_type = 'кубічний';
}

// Новий масив
$newArray = array();
foreach ($D as $value) {
    $newArray[] = $root * $value;
}

// Пошук від’ємних елементів та їх індексів
$negativeCount = 0;
$negativeIndices = array();
foreach ($newArray as $index => $value) {
    if ($value < 0) {
        $negativeCount++;
        $negativeIndices[] = $index;
    }
}
?>
    <div class="formula">
        <img src="https://latex.codecogs.com/svg.image?P=\prod_{i=1}^{10}D_i" alt="Формула добутку">
        <br>
        <img src="https://latex.codecogs.com/svg.image?R=\sqrt[<?php echo ($product>0)?2:3;?>]{P}" alt="Формула кореня">
        <br>
        <img src="https://latex.codecogs.com/svg.image?N_i=R\cdot&space;D_i" alt="Формула нового масиву">
    </div>
    <div class="block">
        <strong>Масив D:</strong> <?php echo implode(', ', $D); ?>
    </div>
    <div class="block">
        <strong>Добуток елементів масиву D (P):</strong> <?php echo $product; ?>
    </div>
    <div class="block">
        <strong><?php echo ucfirst($root_type); ?> корінь з добутку (R):</strong> <?php echo $root; ?>
    </div>
    <div class="block">
        <strong>Новий масив N:</strong> <?php echo implode(', ', $newArray); ?>
    </div>
    <div class="block">
        <strong>Кількість від’ємних елементів у новому масиві:</strong> <?php echo $negativeCount; ?>
        <br>
        <strong>Їх індекси:</strong> <?php echo implode(', ', $negativeIndices); ?>
    </div>
    <div class="block">
        <strong><?php echo $group; ?></strong><br>
        <strong><?php echo $developer; ?></strong><br>
        <strong><?php echo $created; ?></strong><br>
        <strong>Поточна дата:</strong> <?php echo $now; ?>
    </div>
</body>
</html>
