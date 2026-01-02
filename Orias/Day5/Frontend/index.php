<?php
// Frontend/index.php
require_once '../Backend/common.php';

// AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_POST['ajax']))) {
    header('Content-Type: application/json; charset=utf-8');
    $a = $_POST['action'] ?? '';

    if ($a === 'list') {
        $rows = get_all_students();
        echo json_encode(['ok' => true, 'data' => $rows]); exit;
    }

    if ($a === 'add') {
        $hoten = trim($_POST['hoten'] ?? '');
        $gioitinh = $_POST['gioitinh'] ?? '';
        $ngaysinh = $_POST['ngaysinh'] ?? '';
        if ($hoten === '' || $gioitinh === '' || $ngaysinh === '') {
            echo json_encode(['ok' => false, 'error' => 'Thiếu dữ liệu']); exit;
        }
        $id = add_student($hoten, $gioitinh, $ngaysinh);
        echo json_encode(['ok' => true, 'id' => $id]); exit;
    }

    if ($a === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $hoten = trim($_POST['hoten'] ?? '');
        $gioitinh = $_POST['gioitinh'] ?? '';
        $ngaysinh = $_POST['ngaysinh'] ?? '';
        if ($id <= 0 || $hoten === '' || $gioitinh === '' || $ngaysinh === '') {
            echo json_encode(['ok' => false, 'error' => 'Thiếu dữ liệu']); exit;
        }
        update_student($id, $hoten, $gioitinh, $ngaysinh);
        echo json_encode(['ok' => true]); exit;
    }

    if ($a === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['ok' => false, 'error' => 'Thiếu id']); exit;
        }
        delete_student( $id);
        echo json_encode(['ok' => true]); exit;
    }

    echo json_encode(['ok' => false, 'error' => 'action?']); exit;
}

// SSR
$rows = get_all_students();
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Danh sách sinh viên</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="wrap">
    <?php include 'includes/navbar.php'; ?>
    <h2>Danh sách sinh viên</h2>

    <button class="btn" onclick="openAdd()">+ Thêm sinh viên</button>

    <table class="table" id="tb">
        <thead><tr><th>ID</th><th>Họ tên</th><th>Giới tính</th><th>Ngày sinh</th><th>Hành động</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr data-id="<?= $r['id'] ?>">
                <td><?= $r['id'] ?></td>
                <td class="c-hoten"><?= safe_html($r['hoten']) ?></td>
                <td class="c-gioitinh"><?= safe_html($r['gioitinh']) ?></td>
                <td class="c-ngaysinh"><?= safe_html($r['ngaysinh']) ?></td>
                <td>
                    <button class="btn" onclick="openEdit(<?= $r['id'] ?>)">Sửa</button>
                    <button class="btn" onclick="delRow(<?= $r['id'] ?>)">Xóa</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="popup" id="pAdd">
    <div class="pbox">
        <h3>Thêm sinh viên</h3>
        <form id="fAdd">
            <div class="row"><label>Họ tên</label><input name="hoten" required></div>
            <div class="row"><label>Giới tính</label>
                <select name="gioitinh">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="row"><label>Ngày sinh</label><input type="date" name="ngaysinh" required></div>
            <div style="display:flex;gap:8px;justify-content:flex-end">
                <button type="submit" class="btn">Lưu</button>
                <button type="button" class="btn" onclick="closeP('pAdd')">Hủy</button>
            </div>
        </form>
    </div>
</div>

<div class="popup" id="pEdit">
    <div class="pbox">
        <h3>Sửa sinh viên</h3>
        <form id="fEdit">
            <input type="hidden" name="id" id="eid">
            <div class="row"><label>Họ tên</label><input name="hoten" id="ehoten" required></div>
            <div class="row"><label>Giới tính</label>
                <select name="gioitinh" id="egioitinh">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="row"><label>Ngày sinh</label><input type="date" name="ngaysinh" id="engaysinh" required></div>
            <div style="display:flex;gap:8px;justify-content:flex-end">
                <button type="submit" class="btn">Cập nhật</button>
                <button type="button" class="btn" onclick="closeP('pEdit')">Hủy</button>
            </div>
        </form>
    </div>
</div>

<script src="assets/script.js"></script>
</body>
</html>