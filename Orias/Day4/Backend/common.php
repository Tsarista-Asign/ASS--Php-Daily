<?php
// Backend/common.php

// Include pdo_config
require_once '../../config/pdo_config.php';

// Kiểm tra bảng tồn tại
function table_exists() {
    $pdo = connect_db();
    $stmt = $pdo->query("SHOW TABLES LIKE 'MyGuests'");
    return $stmt->rowCount() > 0;
}

// Tạo bảng
function create_table() {
    $pdo = connect_db();
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS MyGuests (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            firstname VARCHAR(30) NOT NULL,
            lastname VARCHAR(30) NOT NULL,
            email VARCHAR(50),
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
}

// Thêm dữ liệu
function insert_data() {
    $pdo = connect_db();
    $stmt = $pdo->prepare("INSERT INTO MyGuests (firstname, lastname, email) VALUES (:fn, :ln, :em)");
    $data = [
        ['fn' => 'John', 'ln' => 'Doe', 'em' => 'john@example.com'],
        ['fn' => 'Jane', 'ln' => 'Smith', 'em' => 'jane@example.com'],
        ['fn' => 'James', 'ln' => 'Johnson', 'em' => 'james@example.com'],
        ['fn' => 'Emily', 'ln' => 'Brown', 'em' => 'emily@example.com'],
        ['fn' => 'Michael', 'ln' => 'Davis', 'em' => 'michael@example.com'],
    ];
    foreach ($data as $row) {
        $stmt->execute($row);
    }
}

// Cập nhật dữ liệu
function update_data() {
    $pdo = connect_db();
    $stmt = $pdo->prepare("UPDATE MyGuests SET firstname = :new WHERE firstname = :old");
    $stmt->execute([':new' => 'Jane', ':old' => 'James']);
}

// Xóa bản ghi
function delete_record() {
    $pdo = connect_db();
    $stmt = $pdo->prepare("DELETE FROM MyGuests WHERE id = :id");
    $stmt->execute([':id' => 3]);
}

// Xóa bảng
function delete_table() {
    $pdo = connect_db();
    $pdo->exec("DROP TABLE IF EXISTS MyGuests");
}

// Lấy dữ liệu
function get_data() {
    $pdo = connect_db();
    if (!table_exists()) return [];
    $stmt = $pdo->query("SELECT id, firstname, lastname, reg_date FROM MyGuests");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hàm an toàn HTML
function safe_html($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}