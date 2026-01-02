<?php
// Frontend/listroles.php
require_once '../Backend/common.php';

require_login();
$pdo = connect_db();
ensure_tables($pdo);

// AJAX (giữ nguyên)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_POST['ajax']))) {
    header('Content-Type: application/json; charset=utf-8');
    $a = $_POST['action'] ?? '';

    if ($a === 'list') {
        $rows = $pdo->query("SELECT id,name FROM roles ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['ok' => true, 'data' => $rows]); exit;
    }
    if (!is_admin()) { json_forbidden(); }

    if ($a === 'add') {
        $name = trim($_POST['name'] ?? '');
        if ($name === '') { echo json_encode(['ok' => false, 'error' => 'Tên rỗng']); exit; }
        $st = $pdo->prepare("INSERT INTO roles(name) VALUES(:n)");
        $st->execute([':n' => $name]);
        echo json_encode(['ok' => true, 'id' => $pdo->lastInsertId()]); exit;
    }
    if ($a === 'update') {
        $id = (int) ($_POST['id'] ?? 0); $name = trim($_POST['name'] ?? '');
        if ($id <= 0 || $name === '') { echo json_encode(['ok' => false, 'error' => 'Thiếu dữ liệu']); exit; }
        $st = $pdo->prepare("UPDATE roles SET name=:n WHERE id=:id");
        $st->execute([':n' => $name, ':id' => $id]);
        echo json_encode(['ok' => true]); exit;
    }
    if ($a === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) { echo json_encode(['ok' => false, 'error' => 'Thiếu id']); exit; }
        $st = $pdo->prepare("DELETE FROM roles WHERE id=:id");
        $st->execute([':id' => $id]);
        echo json_encode(['ok' => true]); exit;
    }
    echo json_encode(['ok' => false, 'error' => 'action?']); exit;
}

// SSR
$rows = $pdo->query("SELECT id,name FROM roles ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Roles</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body data-page="roles">
<div class="wrap">
    <?php include 'includes/navbar.php'; ?>
    <h2>Roles</h2>

    <button class="btn" onclick="openAdd('roles')" <?= is_admin() ? '' : 'disabled title="Chỉ Admin"' ?>>+ Thêm</button>

    <table class="table" id="tb">
        <thead><tr><th>ID</th><th>Name</th><th>Hành động</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr data-id="<?= $r['id'] ?>">
                <td><?= $r['id'] ?></td>
                <td class="c-name"><?= safe_html($r['name']) ?></td>
                <td>
                    <?php if (is_admin()): ?>
                        <button class="btn" onclick="openEdit(<?= $r['id'] ?>, '<?= safe_html($r['name']) ?>', 'roles')">Sửa</button>
                        <button class="btn" onclick="delRow(<?= $r['id'] ?>, 'listroles.php')">Xóa</button>
                    <?php else: ?>
                        <button class="btn" disabled title="Chỉ Admin">Sửa</button>
                        <button class="btn" disabled title="Chỉ Admin">Xóa</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="popup" id="pAdd"><div class="pbox">
    <h3>Thêm Role</h3>
    <form id="fAdd" class="frm">
        <div class="row"><label>Tên</label><input name="name" required></div>
        <div style="display:flex;gap:8px;justify-content:flex-end">
            <button type="submit" class="btn">Lưu</button>
            <button type="button" class="btn" onclick="closeP('pAdd')">Hủy</button>
        </div>
    </form>
</div></div>

<div class="popup" id="pEdit"><div class="pbox">
    <h3>Sửa Role</h3>
    <form id="fEdit" class="frm">
        <input type="hidden" name="id" id="eid">
        <div class="row"><label>Tên</label><input name="name" id="ename" required></div>
        <div style="display:flex;gap:8px;justify-content:flex-end">
            <button type="submit" class="btn">Cập nhật</button>
            <button type="button" class="btn" onclick="closeP('pEdit')">Hủy</button>
        </div>
    </form>
</div></div>

<script>const isAdmin = <?= is_admin() ? 'true' : 'false' ?>;</script>
<script src="assets/script.js"></script>
</body>
</html>