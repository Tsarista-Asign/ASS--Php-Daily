<?php
// Frontend/index.php
require_once '../Backend/common.php';

// AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_POST['ajax']))) {
    header('Content-Type: application/json; charset=utf-8');
    $a = $_POST['action'] ?? '';

    if ($a === 'list') {
        $exists = table_exists();
        $data = $exists ? get_data() : [];
        echo json_encode(['ok' => true, 'exists' => $exists, 'data' => $data]); exit;
    }

    if ($a === 'create_table') {
        create_table();
        echo json_encode(['ok' => true, 'msg' => 'Bảng đã tạo']); exit;
    }

    if ($a === 'insert_data') {
        if (!table_exists()) { echo json_encode(['ok' => false, 'error' => 'Bảng không tồn tại']); exit; }
        insert_data();
        echo json_encode(['ok' => true, 'msg' => 'Dữ liệu đã thêm']); exit;
    }

    if ($a === 'update_data') {
        if (!table_exists()) { echo json_encode(['ok' => false, 'error' => 'Bảng không tồn tại']); exit; }
        update_data();
        echo json_encode(['ok' => true, 'msg' => 'Dữ liệu đã cập nhật']); exit;
    }

    if ($a === 'delete_record') {
        if (!table_exists()) { echo json_encode(['ok' => false, 'error' => 'Bảng không tồn tại']); exit; }
        delete_record();
        echo json_encode(['ok' => true, 'msg' => 'Bản ghi đã xóa']); exit;
    }

    if ($a === 'delete_table') {
        delete_table();
        echo json_encode(['ok' => true, 'msg' => 'Bảng đã xóa']); exit;
    }

    echo json_encode(['ok' => false, 'error' => 'action?']); exit;
}

// SSR (ban đầu)
$exists = table_exists();
$rows = $exists ? get_data() : [];
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>MySQL Demo</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="wrap">
    <?php include 'includes/navbar.php'; ?>
    <div style="margin-bottom:15px;">
        <button class="btn" onclick="runAction('create_table')">Tạo bảng</button>
        <button class="btn" onclick="runAction('insert_data')">Thêm dữ liệu</button>
        <button class="btn" onclick="runAction('update_data')">Cập nhật dữ liệu</button>
        <button class="btn" onclick="runAction('delete_record', 'Xóa bản ghi id=3?')">Xóa 1 bản ghi</button>
        <button class="btn" onclick="runAction('delete_table', 'Xóa bảng?')">Xóa bảng</button>
    </div>
    <div id="msg" class="msg"></div>

    <h2>Danh sách nhân viên</h2>
    <table class="table" id="tb" style="<?= $exists ? '' : 'display:none' ?>">
        <thead><tr><th>Id</th><th>Firstname</th><th>Lastname</th><th>Reg_Date</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= safe_html($r['firstname']) ?></td>
                <td><?= safe_html($r['lastname']) ?></td>
                <td><?= safe_html($r['reg_date']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p id="no-table" style="<?= $exists ? 'display:none' : '' ?>"><b>Bảng MyGuests chưa tồn tại hoặc đã bị xóa.</b></p>
</div>

<script src="assets/script.js"></script>
</body>
</html>