<?php
// Config Local
$local = [
    'host' => 'localhost',
    'name' => 'ass',
    'user' => 'root',
    'pass' => 'LongK@170105'
];

// Config InfinityFree
$remote = [
    'host' => 'sql112.infinityfree.com',
    'name' => 'if0_39708432_ass',
    'user' => 'if0_39708432',
    'pass' => 'LongK171'
];

define("ADMIN_USER", "admin");
define("ADMIN_PASS", "admin");

// Biến kết nối toàn cục PDO
global $pdo;
$pdo = null;

function connect_db() {
    global $pdo, $local, $remote;
    if ($pdo) return $pdo;

    // Thử Local trước
    try {
        $dsn = "mysql:host={$local['host']};dbname={$local['name']};charset=utf8";
        $pdo = new PDO($dsn, $local['user'], $local['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e1) {
        // Nếu thất bại → thử Remote
        try {
            $dsn = "mysql:host={$remote['host']};dbname={$remote['name']};charset=utf8";
            $pdo = new PDO($dsn, $remote['user'], $remote['pass']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e2) {
            die("❌ Lỗi kết nối DB (Local + Remote): " . $e2->getMessage());
        }
    }
    return $pdo;
}

function disconnect_db() {
    global $pdo;
    $pdo = null;
}
