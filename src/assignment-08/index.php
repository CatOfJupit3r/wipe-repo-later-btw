<?php
session_start();

$group = "Група: СП-41";
$developer = "Розробник: Бармак Роман Миколайович";
$created = "Дата створення: 27.10.2025";
$now = date("d.m.Y H:i");

function safe($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$file = __DIR__ . '/data.xml';

if (!file_exists($file)) {
    file_put_contents($file, "<?xml version=\"1.0\" encoding=\"UTF-8\"?><books></books>");
}

$xml = simplexml_load_file($file);
if ($xml === false) {
    $error = 'Cannot parse XML file.';
    $xml = new SimpleXMLElement('<books></books>');
}

function flash_set($m) { $_SESSION['_flash'] = $m; }
function flash_get() { $m = $_SESSION['_flash'] ?? null; unset($_SESSION['_flash']); return $m; }

function remove_child_at_index($simpleParent, $childTag, $index) {
    $domParent = dom_import_simplexml($simpleParent);
    if (!$domParent) return false;
    $toRemove = [];
    foreach ($domParent->childNodes as $node) {
        if ($node->nodeType === XML_ELEMENT_NODE && $node->nodeName === $childTag) {
            $toRemove[] = $node;
        }
    }
    if (!isset($toRemove[$index])) return false;
    $domParent->removeChild($toRemove[$index]);
    return true;
}

function dom_remove_book_at_file($file, $bid) {
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    if (!@$dom->load($file)) return false;
    $books = $dom->getElementsByTagName('book');
    if ($books->length <= $bid) return false;
    $node = $books->item($bid);
    $node->parentNode->removeChild($node);
    return $dom->save($file) !== false;
}

function dom_add_character_to_book_file($file, $bid, $name, $desc) {
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    if (!@$dom->load($file)) return false;
    $books = $dom->getElementsByTagName('book');
    if ($books->length <= $bid) return false;
    $book = $books->item($bid);

    $chars = null;
    foreach ($book->childNodes as $cn) {
        if ($cn->nodeType === XML_ELEMENT_NODE && $cn->nodeName === 'characters') { $chars = $cn; break; }
    }
    if (!$chars) {
        $chars = $dom->createElement('characters');
        $book->appendChild($chars);
    }
    $character = $dom->createElement('character');
    $n = $dom->createElement('name', htmlspecialchars($name));
    $d = $dom->createElement('desc', htmlspecialchars($desc));
    $character->appendChild($n);
    $character->appendChild($d);
    $chars->appendChild($character);
    return $dom->save($file) !== false;
}

function dom_remove_character_at_file($file, $bid, $cid) {
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    if (!@$dom->load($file)) return false;
    $books = $dom->getElementsByTagName('book');
    if ($books->length <= $bid) return false;
    $book = $books->item($bid);

    $chars = null;
    foreach ($book->childNodes as $cn) {
        if ($cn->nodeType === XML_ELEMENT_NODE && $cn->nodeName === 'characters') { $chars = $cn; break; }
    }
    if (!$chars) return false;
    $characters = [];
    foreach ($chars->childNodes as $cnode) {
        if ($cnode->nodeType === XML_ELEMENT_NODE && $cnode->nodeName === 'character') $characters[] = $cnode;
    }
    if (!isset($characters[$cid])) return false;
    $chars->removeChild($characters[$cid]);
    return $dom->save($file) !== false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add_book') {
        $title = trim($_POST['title'] ?? '');
        $plot = trim($_POST['plot'] ?? '');
        if ($title !== '') {
            $b = $xml->addChild('book');
            $b->addChild('title', $title);
            $b->addChild('plot', $plot);
            $b->addChild('characters');
            $xml->asXML($file);
            flash_set('Книгу додано.');
        } else {
            flash_set('Заголовок не може бути порожнім.');
        }
        header('Location: index.php'); exit;
    }
    if ($action === 'delete_book' && isset($_POST['bid'])) {
        $bid = (int)$_POST['bid'];
            if (isset($xml->book[$bid])) {
                if (dom_remove_book_at_file($file, $bid)) {
                    $xml = simplexml_load_file($file);
                    flash_set('Книгу видалено.');
                } else {
                    flash_set('Помилка видалення книги.');
                }
            }
        header('Location: index.php'); exit;
    }
    if ($action === 'add_char' && isset($_POST['bid'])) {
        $bid = (int)$_POST['bid'];
        $name = trim($_POST['cname'] ?? '');
        $desc = trim($_POST['cdesc'] ?? '');
        if ($name !== '' && isset($xml->book[$bid])) {
            if (dom_add_character_to_book_file($file, $bid, $name, $desc)) {
                $xml = simplexml_load_file($file);
                flash_set('Персонажа додано.');
            } else {
                flash_set('Помилка додавання персонажа.');
            }
        } else {
            flash_set('Некоректні дані персонажа.');
        }
        header('Location: index.php'); exit;
    }
    if ($action === 'delete_char' && isset($_POST['bid']) && isset($_POST['cid'])) {
        $bid = (int)$_POST['bid'];
        $cid = (int)$_POST['cid'];
        if (isset($xml->book[$bid]->characters->character[$cid])) {
            if (dom_remove_character_at_file($file, $bid, $cid)) {
                $xml = simplexml_load_file($file);
                flash_set('Персонажа видалено.');
            } else {
                flash_set('Помилка видалення персонажа.');
            }
        }
        header('Location: index.php'); exit;
    }
    if ($action === 'edit_book' && isset($_POST['bid'])) {
        $bid = (int)$_POST['bid'];
        $title = trim($_POST['title'] ?? '');
        $plot = trim($_POST['plot'] ?? '');
        if (isset($xml->book[$bid]) && $title !== '') {
            $xml->book[$bid]->title = $title;
            $xml->book[$bid]->plot = $plot;
            $xml->asXML($file);
            flash_set('Книгу оновлено.');
        } else {
            flash_set('Помилка оновлення.');
        }
        header('Location: index.php'); exit;
    }
}

$flash = flash_get();

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Assignment 08 - XML в PHP</title>
    <style>
        .block { border:1px solid #ddd; padding:8px; margin-bottom:10px; }
        .book { border:1px solid #ccc; padding:8px; margin:8px 0; }
        .chars { margin-left:20px; }
        form.inline { display:inline; }
    </style>
</head>
<body>
<div class="block">
    <p><?php echo safe($group); ?></p>
    <p><?php echo safe($developer); ?></p>
    <p><?php echo safe($created); ?></p>
    <p>Поточна дата: <?php echo safe($now); ?></p>
</div>

<a href="../index.php">← Назад</a>

<?php if (!empty($flash)) echo '<div style="color:green">'.safe($flash)."</div>"; ?>

<h2>XML - Книги</h2>

<h3>Додати книгу</h3>
<form method="post">
    <input type="hidden" name="action" value="add_book">
    <div><label>Назва: <input type="text" name="title" required></label></div>
    <div><label>Сюжет: <br><textarea name="plot" rows="3" cols="60"></textarea></label></div>
    <div><button type="submit">Додати книгу</button></div>
</form>

<h3>Існуючі книги</h3>
<?php $bookCounter = 0; foreach ($xml->book as $book) { $bid = $bookCounter; ?>
    <div class="book">
    <strong><?php echo ($bid + 1) . '. ' . safe((string)$book->title); ?></strong>
        <form class="inline" method="post" style="margin-left:10px;">
            <input type="hidden" name="action" value="delete_book">
            <input type="hidden" name="bid" value="<?php echo $bid; ?>">
            <button type="submit" onclick="return confirm('Видалити книгу?')">Видалити книгу</button>
        </form>
        <div><em><?php echo nl2br(safe((string)$book->plot)); ?></em></div>

        <h4>Персонажі</h4>
        <div class="chars">
            <?php if (isset($book->characters) && isset($book->characters->character)) {
                $charCounter = 0;
                foreach ($book->characters->character as $ch) { $cid = $charCounter; ?>
                    <div>
                        <?php echo safe((string)$ch->name) . ' - ' . safe((string)$ch->desc); ?>
                        <form class="inline" method="post">
                            <input type="hidden" name="action" value="delete_char">
                            <input type="hidden" name="bid" value="<?php echo $bid; ?>">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <button type="submit" onclick="return confirm('Видалити персонажа?')">Видалити</button>
                        </form>
                    </div>
            <?php $charCounter++; }
            } else { echo '<div>Немає персонажів.</div>'; } ?>

            <h5>Додати персонажа</h5>
            <form method="post">
                <input type="hidden" name="action" value="add_char">
                <input type="hidden" name="bid" value="<?php echo $bid; ?>">
                <div><label>Ім'я: <input type="text" name="cname" required></label></div>
                <div><label>Опис: <input type="text" name="cdesc"></label></div>
                <div><button type="submit">Додати персонажа</button></div>
            </form>
        </div>

        <h5>Редагувати книгу</h5>
        <form method="post">
            <input type="hidden" name="action" value="edit_book">
            <input type="hidden" name="bid" value="<?php echo $bid; ?>">
            <div><label>Назва: <input type="text" name="title" value="<?php echo safe((string)$book->title); ?>" required></label></div>
            <div><label>Сюжет: <br><textarea name="plot" rows="3" cols="60"><?php echo safe((string)$book->plot); ?></textarea></label></div>
            <div><button type="submit">Зберегти</button></div>
        </form>
    </div>
<?php $bookCounter++; } ?>

<h3>RAW XML</h3>
<pre><?php echo safe($xml->asXML()); ?></pre>
</body>
</html>
