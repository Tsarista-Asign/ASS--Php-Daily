<?php
// Config Local
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'ass');
// define('DB_USER', 'root');
// define('DB_PASS', '170105');

// Config InfinityFree
define('DB_HOST', 'sql206.infinityfree.com');
define('DB_NAME', 'if0_39714427_btvn');
define('DB_USER', 'if0_39714427');
define('DB_PASS', 'Chia1203');

// Biến kết nối toàn cục PDO
global $pdo;
$pdo = null;

function connect_db() {
    global $pdo;
    if (!$pdo) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
            $pdo = new PDO($dsn, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Lỗi kết nối: " . $e->getMessage());
        }
    }
    return $pdo;
}

function disconnect_db() {
    global $pdo;
    $pdo = null;
}
?>



