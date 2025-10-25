<?php
// Simple assignment-06 application using mysqli (procedural)
session_start();

// flash message carried through a redirect
$flash = null;
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}

// Developer info
$group = "Група: СП-41";
$developer = "Розробник: Бармак Роман Миколайович";
$created = "Дата створення: 25.10.2025";
$now = date("d.m.Y H:i");

function safe($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// DB credentials (match docker-compose)
$DB_HOST = getenv('DB_HOST') ?: 'db';
$DB_USER = getenv('DB_USER') ?: 'appuser';
$DB_PASS = getenv('DB_PASS') ?: 'apppass';
$DB_NAME = getenv('DB_NAME') ?: 'assignment06';

// Connect to DB
$mysqli = @mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$mysqli) {
    $db_error = 'Cannot connect to DB: ' . mysqli_connect_error();
} else {
    mysqli_set_charset($mysqli, 'utf8mb4');
    // Ensure table exists
    $create = "CREATE TABLE IF NOT EXISTS people (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        age INT NOT NULL,
        email VARCHAR(150) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    mysqli_query($mysqli, $create);
}

// Simple single-user auth (username/password)
$AUTH_USER = 'admin';
$AUTH_PASS = 'Secret123'; // plaintext for simplicity (beginner-friendly)

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset(); session_destroy(); header('Location: index.php'); exit;
}

// Handle login
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

// Require auth for CRUD actions and listing
if (!isset($_SESSION['user'])) {
    // show login form
    ?>
    <!doctype html>
    <html><head><meta charset="utf-8"><title>Assignment 06 - Login</title></head><body>
    <div class="block">
        <p><?php echo safe($group); ?></p>
        <p><?php echo safe($developer); ?></p>
        <p><?php echo safe($created); ?></p>
        <p>Поточна дата: <?php echo safe($now); ?></p>
    </div>
    <h2>Авторизація</h2>
    <?php if ($login_error) echo '<div style="color:red">'.safe($login_error).'</div>'; ?>
    <form method="post">
        <div><label>Логін: <input type="text" name="user"></label></div>
        <div><label>Пароль: <input type="password" name="pass"></label></div>
        <div><button name="login" type="submit">Увійти</button></div>
    </form>
    </body></html>
    <?php
    exit;
}

// From this point user is authenticated

// Actions: add, edit, delete
$messages = [];
if (!$mysqli) {
    $messages[] = isset($db_error) ? $db_error : 'DB not available';
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action']) && $_POST['action'] === 'add') {
            $name = trim($_POST['name'] ?? '');
            $age = (int)($_POST['age'] ?? 0);
            $email = trim($_POST['email'] ?? '');
            if ($name === '' || $age <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $messages[] = 'Некоректні дані для додавання';
            } else {
                $stmt = mysqli_prepare($mysqli, "INSERT INTO people (name,age,email) VALUES (?,?,?)");
                mysqli_stmt_bind_param($stmt, 'sis', $name, $age, $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                // set flash and redirect to avoid re-showing edit form (Post/Redirect/Get)
                $_SESSION['flash'] = 'Додано запис.';
                header('Location: index.php');
                exit;
            }
        }
        if (isset($_POST['action']) && $_POST['action'] === 'update' && isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            $name = trim($_POST['name'] ?? '');
            $age = (int)($_POST['age'] ?? 0);
            $email = trim($_POST['email'] ?? '');
            if ($id <= 0 || $name === '' || $age <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $messages[] = 'Некоректні дані для оновлення';
            } else {
                $stmt = mysqli_prepare($mysqli, "UPDATE people SET name=?, age=?, email=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, 'sisi', $name, $age, $email, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION['flash'] = 'Оновлено запис.';
                header('Location: index.php');
                exit;
            }
        }
        if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            if ($id > 0) {
                $stmt = mysqli_prepare($mysqli, "DELETE FROM people WHERE id=?");
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION['flash'] = 'Видалено запис.';
                header('Location: index.php');
                exit;
            }
        }
    }

    // Sorting (derive value safely to avoid undefined index warnings)
    $allowed = ['id','name','age','email','created_at'];
    $requested_sort = $_GET['sort'] ?? 'id';
    $sort = in_array($requested_sort, $allowed, true) ? $requested_sort : 'id';
    $order = (isset($_GET['order']) && strtolower($_GET['order']) === 'desc') ? 'DESC' : 'ASC';

    // Build query using validated column name. Wrap column with backticks as it's from a whitelist.
    $order_by = "`" . $sort . "` " . $order;
    $sql = "SELECT id,name,age,email,created_at FROM people ORDER BY " . $order_by;
    $res = mysqli_query($mysqli, $sql);
    if ($res === false) {
        $messages[] = 'DB error: ' . mysqli_error($mysqli);
        $rows = [];
    }
    $rows = [];
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
        mysqli_free_result($res);
    }
}

// Helper to build sort links toggling order
function sort_link($col, $label) {
    $current = $_GET['sort'] ?? 'id';
    $order = (isset($_GET['order']) && strtolower($_GET['order']) === 'desc') ? 'desc' : 'asc';
    $new_order = ($current === $col && $order === 'asc') ? 'desc' : 'asc';
    return '<a href="?sort=' . rawurlencode($col) . '&order=' . $new_order . '">' . safe($label) . '</a>';
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Assignment 06 - DB App</title>
    <style>
        .block { border:1px solid #ddd; padding:8px; margin-bottom:10px; }
        .table { display:table; border-collapse:collapse; width:100%; }
        .row { display:table-row; }
        .cell { display:table-cell; padding:6px; border:1px solid #ccc; }
        .actions { white-space:nowrap; }
        form.inline { display:inline; }
    </style>
</head>
<body>
<div class="block">
    <p><?php echo safe($group); ?></p>
    <p><?php echo safe($developer); ?></p>
    <p><?php echo safe($created); ?></p>
    <p>Поточна дата: <?php echo safe($now); ?></p>
    <p>Користувач: <?php echo safe($_SESSION['user']); ?> — <a href="?action=logout">Вийти</a></p>
</div>

<?php if ($flash) echo '<div style="color:green">'.safe($flash).'</div>'; ?>
<?php foreach ($messages as $m) echo '<div style="color:green">'.safe($m).'</div>'; ?>

<h2>Список записів</h2>
<div class="table">
    <div class="row">
        <div class="cell"><?php echo sort_link('id','ID'); ?></div>
        <div class="cell"><?php echo sort_link('name','Ім’я'); ?></div>
        <div class="cell"><?php echo sort_link('age','Вік'); ?></div>
        <div class="cell"><?php echo sort_link('email','Email'); ?></div>
        <div class="cell"><?php echo sort_link('created_at','Створено'); ?></div>
        <div class="cell">Дії</div>
    </div>
    <?php if (!empty($rows)) { foreach ($rows as $r) { ?>
        <div class="row">
            <div class="cell"><?php echo safe($r['id']); ?></div>
            <div class="cell"><?php echo safe($r['name']); ?></div>
            <div class="cell"><?php echo safe($r['age']); ?></div>
            <div class="cell"><?php echo safe($r['email']); ?></div>
            <div class="cell"><?php echo safe($r['created_at']); ?></div>
            <div class="cell actions">
                <a href="?edit=<?php echo rawurlencode($r['id']); ?>">Редагувати</a>
                <form class="inline" method="post" onsubmit="return confirm('Видалити?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?php echo safe($r['id']); ?>">
                    <button type="submit">Видалити</button>
                </form>
            </div>
        </div>
    <?php } } else { ?>
        <div class="row"><div class="cell" colspan="6">Немає записів.</div></div>
    <?php } ?>
</div>

<?php
// If editing, load record
$edit = null;
if (isset($_GET['edit']) && $mysqli) {
    $id = (int)$_GET['edit'];
    if ($id > 0) {
        $stmt = mysqli_prepare($mysqli, "SELECT id,name,age,email FROM people WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $edit = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
    }
}
?>

<h2><?php echo $edit ? 'Редагувати запис' : 'Додати запис'; ?></h2>
<form method="post">
    <input type="hidden" name="action" value="<?php echo $edit ? 'update' : 'add'; ?>">
    <?php if ($edit) { ?><input type="hidden" name="id" value="<?php echo safe($edit['id']); ?>"><?php } ?>
    <div><label>Ім'я: <input type="text" name="name" value="<?php echo $edit ? safe($edit['name']) : ''; ?>"></label></div>
    <div><label>Вік: <input type="number" name="age" value="<?php echo $edit ? safe($edit['age']) : ''; ?>"></label></div>
    <div><label>Email: <input type="email" name="email" value="<?php echo $edit ? safe($edit['email']) : ''; ?>"></label></div>
    <div><button type="submit"><?php echo $edit ? 'Оновити' : 'Додати'; ?></button></div>
</form>

</body>
</html>
