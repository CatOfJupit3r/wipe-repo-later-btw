<?php
// api.php - JSON endpoints for assignment-07 (list, add)
header('Content-Type: application/json; charset=utf-8');
session_start();
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'auth'], JSON_UNESCAPED_UNICODE);
    exit;
}

// simple helper
function json_out($a) { echo json_encode($a, JSON_UNESCAPED_UNICODE); exit; }

$action = $_REQUEST['action'] ?? 'list';

$DB_HOST = getenv('DB_HOST') ?: 'db';
$DB_USER = getenv('DB_USER') ?: 'appuser';
$DB_PASS = getenv('DB_PASS') ?: 'apppass';
$DB_NAME = getenv('DB_NAME') ?: 'assignment06';

$mysqli = @mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$mysqli) json_out(['success' => false, 'error' => 'DB connect failed']);
mysqli_set_charset($mysqli, 'utf8mb4');

// ensure table exists
$create = "CREATE TABLE IF NOT EXISTS ajax_people (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    email VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
mysqli_query($mysqli, $create);

if ($action === 'list') {
    $res = mysqli_query($mysqli, "SELECT id,name,age,email,created_at FROM ajax_people ORDER BY id DESC LIMIT 200");
    $rows = [];
    while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    json_out(['success' => true, 'data' => $rows]);
}

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $age = (int)($_POST['age'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    if ($name === '' || $age <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_out(['success' => false, 'error' => 'Invalid input']);
    }
    $stmt = mysqli_prepare($mysqli, "INSERT INTO ajax_people (name,age,email) VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt, 'sis', $name, $age, $email);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    if ($ok) json_out(['success' => true]);
    json_out(['success' => false, 'error' => 'Insert failed']);
}

json_out(['success' => false, 'error' => 'Unknown action']);
