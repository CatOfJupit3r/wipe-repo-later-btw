<?php
$group = "Група: СП-41";
$developer = "Розробник: Бармак Роман Миколайович";
$created = "Дата створення: 15.10.2025";
$now = date("d.m.Y H:i");

$amountInput = isset($_GET['amount']) ? $_GET['amount'] : '';
$amount = '';
$error = '';
$resultClassic = '';
$resultAlternative = '';

if ($amountInput !== '') {
    $amount = (int) $amountInput;

    if ($amount < 1 || $amount > 999) {
        $error = "Введіть число від 1 до 999";
    } else {
        $lastTwo = $amount % 100;
        $last = $amount % 10;

        $formClassic = "гривень";

        if ($lastTwo >= 11 && $lastTwo <= 14) {
            $formClassic = "гривень";
        } else {
            switch ($last) {
                case 1:
                    $formClassic = "гривня";
                    break;
                case 2:
                case 3:
                case 4:
                    $formClassic = "гривні";
                    break;
                default:
                    $formClassic = "гривень";
                    break;
            }
        }

        // альтернативний синтаксис
        $formAlt = "гривень";

        if ($lastTwo >= 11 && $lastTwo <= 14) {
            $formAlt = "гривень";
        } else {
            switch ($last):
                case 1:
                    $formAlt = "гривня";
                    break;
                case 2:
                case 3:
                case 4:
                    $formAlt = "гривні";
                    break;
                default:
                    $formAlt = "гривень";
                    break;
            endswitch;
        }

        $resultClassic = $amount . " " . $formClassic;
        $resultAlternative = $amount . " " . $formAlt;
    }
}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Форми слова гривня</title>
</head>
<body>
    <h1>Визначення правильної форми слова гривня</h1>
    <p><?php echo htmlspecialchars($group); ?></p>
    <p><?php echo htmlspecialchars($developer); ?></p>
    <p><?php echo htmlspecialchars($created); ?></p>
    <p>Поточна дата: <?php echo htmlspecialchars($now); ?></p>
    <a href="../index.php">← Назад</a>
    
    <hr>
    <form method="get">
        <label>Число (1-999):</label>
        <input type="number" name="amount" min="1" max="999" value="<?php echo htmlspecialchars($amountInput); ?>">
        <button type="submit">Порахувати</button>
    </form>

    <?php if ($error !== '') { ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <?php if ($resultClassic !== '' && $resultAlternative !== '') { ?>
        <p>Класичний switch: <?php echo htmlspecialchars($resultClassic); ?></p>
        <p>Альтернативний switch: <?php echo htmlspecialchars($resultAlternative); ?></p>
    <?php } ?>

</body>
</html>
