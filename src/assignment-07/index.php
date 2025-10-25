<?php
// Assignment 07 - AJAX demo (jQuery, PHP, MySQLi)
session_start();

// Simple auth (same as assignment-06)
$AUTH_USER = 'admin';
$AUTH_PASS = 'Secret123';

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset(); session_destroy(); header('Location: index.php'); exit;
}

// Handle login POST
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user = isset($_POST['user']) ? trim($_POST['user']) : '';
    $pass = isset($_POST['pass']) ? $_POST['pass'] : '';
    if ($user === $AUTH_USER && $pass === $AUTH_PASS) {
        $_SESSION['user'] = $user;
        header('Location: index.php'); exit;
    } else {
        $login_error = 'Невірні облікові дані';
    }
}

$group = "Група: СП-41";
$developer = "Розробник: Бармак Роман Миколайович";
$created = "Дата створення: 25.10.2025";
$now = date("d.m.Y H:i");

function safe($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// If not authenticated, show login form and exit
if (!isset($_SESSION['user'])) {
    ?>
    <!doctype html>
    <html><head><meta charset="utf-8"><title>Assignment 07 - Login</title></head><body>
    <div class="block">
        <p><?php echo safe($group); ?></p>
        <p><?php echo safe($developer); ?></p>
        <p><?php echo safe($created); ?></p>
        <p>Поточна дата: <?php echo safe($now); ?></p>
    </div>
    <h2>Авторизація (для доступу до AJAX демо)</h2>
    <?php if ($login_error) echo '<div style="color:red">'.safe($login_error).'</div>'; ?>
    <form method="post">
        <div><label>Логін: <input type="text" name="user" value="admin"></label></div>
        <div><label>Пароль: <input type="password" name="pass"></label></div>
        <div><button name="login" type="submit">Увійти</button></div>
    </form>
    </body></html>
    <?php
    exit;
}

// DB connection (reuse same DB from docker-compose)
$DB_HOST = getenv('DB_HOST') ?: 'db';
$DB_USER = getenv('DB_USER') ?: 'appuser';
$DB_PASS = getenv('DB_PASS') ?: 'apppass';
$DB_NAME = getenv('DB_NAME') ?: 'assignment06';

$mysqli = @mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli) mysqli_set_charset($mysqli, 'utf8mb4');

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Assignment 07 - AJAX</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .block { border:1px solid #ddd; padding:8px; margin-bottom:10px; }
        .hint { color: #006; font-style: italic; }
        .list { margin-top:10px; }
        table { border-collapse:collapse; width:100%; }
        td,th { border:1px solid #ccc; padding:6px; }
    </style>
</head>
<body>
<div class="block">
    <p><?php echo safe($group); ?></p>
    <p><?php echo safe($developer); ?></p>
    <p><?php echo safe($created); ?></p>
    <p>Поточна дата: <?php echo safe($now); ?></p>
</div>

<h2>Пошук підказок (AJAX)</h2>
<p>Введіть ім'я (англійські букви) і отримаєте підказки:</p>
<input type="text" id="q" placeholder="Start typing..."> <span id="hint" class="hint"></span>

<h2>Додати запис (AJAX POST)</h2>
<form id="addForm">
    <div><label>Ім'я: <input type="text" name="name" required></label></div>
    <div><label>Вік: <input type="number" name="age" required></label></div>
    <div><label>Email: <input type="email" name="email" required></label></div>
    <div><button type="submit">Додати</button></div>
</form>

<div class="list">
    <h2>Список записів (JSON)</h2>
    <div id="records">Завантаження...</div>
</div>

<script>
$(function(){
    // show hints using GET to gethint.php
    $('#q').on('keyup', function(){
        var v = $(this).val();
        if (v.length === 0) { $('#hint').text(''); return; }
        $.get('gethint.php', { q: v }, function(data){
            $('#hint').text(data);
        });
    });

    // load records as JSON
    function loadRecords(){
        $.getJSON('api.php', { action: 'list' }, function(resp){
            if (!resp || !resp.data) { $('#records').html('Немає даних'); return; }
            var html = '<table><tr><th>ID</th><th>Ім\'я</th><th>Вік</th><th>Email</th></tr>';
            $.each(resp.data, function(i,r){
                html += '<tr><td>'+r.id+'</td><td>'+escapeHtml(r.name)+'</td><td>'+r.age+'</td><td>'+escapeHtml(r.email)+'</td></tr>';
            });
            html += '</table>';
            $('#records').html(html);
        }).fail(function(xhr){ $('#records').html('Помилка завантаження'); });
    }

    // helper to escape
    function escapeHtml(s){ return $('<div>').text(s).html(); }

    loadRecords();

    // submit add form via AJAX POST
    $('#addForm').on('submit', function(e){
        e.preventDefault();
        var data = $(this).serialize() + '&action=add';
        $.post('api.php', data, function(resp){
            if (resp && resp.success) {
                alert('Додано');
                $('#addForm')[0].reset();
                loadRecords();
            } else {
                alert('Помилка: ' + (resp && resp.error ? resp.error : 'unknown'));
            }
        }, 'json').fail(function(){ alert('Запит не вдався'); });
    });
});
</script>
</body>
</html>
