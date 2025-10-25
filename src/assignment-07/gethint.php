<?php
// gethint.php - return comma-separated name suggestions (requires auth)
header('Content-Type: text/plain; charset=utf-8');
session_start();
if (!isset($_SESSION['user'])) { echo ''; exit; }

$q = isset($_REQUEST['q']) ? trim($_REQUEST['q']) : '';
if ($q === '') { echo ''; exit; }

// DB connection (same as other assignments)
$DB_HOST = getenv('DB_HOST') ?: 'db';
$DB_USER = getenv('DB_USER') ?: 'appuser';
$DB_PASS = getenv('DB_PASS') ?: 'apppass';
$DB_NAME = getenv('DB_NAME') ?: 'assignment06';

$mysqli = @mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$mysqli) { echo ''; exit; }
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

$param = mb_strtolower($q, 'UTF-8');
$like = $param . '%';
$stmt = mysqli_prepare($mysqli, "SELECT name FROM ajax_people WHERE LOWER(name) LIKE ? LIMIT 10");
mysqli_stmt_bind_param($stmt, 's', $like);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$hints = [];
while ($row = mysqli_fetch_assoc($res)) {
    $hints[] = $row['name'];
}
mysqli_stmt_close($stmt);

if (empty($hints)) echo 'no suggestion'; else echo implode(', ', $hints);
