<?php
// backend/common.php

// Include pdo_config
require_once '../../config/pdo_config.php';

// Đảm bảo bảng tồn tại
function ensure_table_exists() {
    $pdo = connect_db();
    $sql = "
        CREATE TABLE IF NOT EXISTS sinhvien (
            id INT AUTO_INCREMENT PRIMARY KEY,
            hoten VARCHAR(255) NOT NULL,
            gioitinh VARCHAR(10) NOT NULL,
            ngaysinh DATE NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sql);
}

// Lấy tất cả sinh viên
function get_all_students() {
    $pdo = connect_db();
    ensure_table_exists();
    $stmt = $pdo->query("SELECT id, hoten, gioitinh, ngaysinh FROM sinhvien ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy sinh viên theo ID
function get_student($id) {
    $pdo = connect_db();
    ensure_table_exists();
    $stmt = $pdo->prepare("SELECT id, hoten, gioitinh, ngaysinh FROM sinhvien WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Thêm sinh viên
function add_student($hoten, $gioitinh, $ngaysinh) {
    $pdo = connect_db();
    ensure_table_exists();
    $stmt = $pdo->prepare("INSERT INTO sinhvien (hoten, gioitinh, ngaysinh) VALUES (:hoten, :gioitinh, :ngaysinh)");
    $stmt->execute([':hoten' => $hoten, ':gioitinh' => $gioitinh, ':ngaysinh' => $ngaysinh]);
    return $pdo->lastInsertId();
}

// Cập nhật sinh viên
function update_student($id, $hoten, $gioitinh, $ngaysinh) {
    $pdo = connect_db();
    ensure_table_exists();
    $stmt = $pdo->prepare("UPDATE sinhvien SET hoten = :hoten, gioitinh = :gioitinh, ngaysinh = :ngaysinh WHERE id = :id");
    $stmt->execute([':hoten' => $hoten, ':gioitinh' => $gioitinh, ':ngaysinh' => $ngaysinh, ':id' => $id]);
}

// Xóa sinh viên
function delete_student($id) {
    $pdo = connect_db();
    ensure_table_exists();
    $stmt = $pdo->prepare("DELETE FROM sinhvien WHERE id = :id");
    $stmt->execute([':id' => $id]);
}

// Hàm an toàn HTML
function safe_html($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}