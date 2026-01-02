<?php
// Frontend/index.php
require_once '../Backend/common.php';

require_login();
$pdo = connect_db();

// AJAX: ensure tables (Admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_POST['ajax']))) {
    header('Content-Type: application/json; charset=utf-8');
    $action = $_POST['action'] ?? '';
    if ($action === 'ensure') {
        if (!is_admin()) { json_forbidden(); }
        try { ensure_tables($pdo); echo json_encode(['ok' => true, 'msg' => 'Đã đảm bảo các bảng']); }
        catch (Throwable $e) { echo json_encode(['ok' => false, 'error' => $e->getMessage()]); }
        exit;
    }
    echo json_encode(['ok' => false, 'error' => 'action?']); exit;
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body data-page="index">
<div class="wrap">
    <?php include 'includes/navbar.php'; ?>
    <div class="card">
        <h2>Chào mừng</h2>
        <p>Admin có thể đảm bảo/tạo bảng DB khi cần.</p>
        <button id="ensure" class="btn" <?= is_admin() ? '' : 'disabled title="Chỉ Admin"' ?>>Đảm bảo các bảng</button>
        <div id="m" class="msg"></div>
    </div>
</div>
<script>const isAdmin = <?= is_admin() ? 'true' : 'false' ?>;</script>
<script src="assets/script.js"></script>
</body>
</html>