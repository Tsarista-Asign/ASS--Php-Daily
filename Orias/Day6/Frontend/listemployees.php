<?php
// Frontend/listemployees.php
require_once '../Backend/common.php';

require_login();
$pdo = connect_db();
ensure_tables($pdo);

// AJAX (giữ nguyên)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_POST['ajax']))) {
    header('Content-Type: application/json; charset=utf-8');
    $a = $_POST['action'] ?? '';

    if ($a === 'list') {
        $rows = $pdo->query("
            SELECT e.id, e.first, e.last, e.department_id, e.role_id,
                   d.name AS department_name, r.name AS role_name
            FROM employees e
              LEFT JOIN departments d ON d.id=e.department_id
              LEFT JOIN roles       r ON r.id=e.role_id
            ORDER BY e.id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['ok' => true, 'data' => $rows]); exit;
    }
    if ($a === 'options') {
        $deps = $pdo->query("SELECT id, name FROM departments ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
        $roles = $pdo->query("SELECT id, name FROM roles ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['ok' => true, 'departments' => $deps, 'roles' => $roles]); exit;
    }
    if (!is_admin()) { json_forbidden(); }

    if ($a === 'add') {
        $first = trim($_POST['first'] ?? ''); $last = trim($_POST['last'] ?? '');
        $dep = (int) ($_POST['department_id'] ?? 0); $role = (int) ($_POST['role_id'] ?? 0);
        if ($first === '' || $last === '') { echo json_encode(['ok' => false, 'error' => 'Thiếu tên']); exit; }
        $st = $pdo->prepare("INSERT INTO employees(first,last,department_id,role_id) VALUES(:f,:l,:d,:r)");
        $st->execute([':f' => $first, ':l' => $last, ':d' => $dep ?: null, ':r' => $role ?: null]);
        echo json_encode(['ok' => true, 'id' => $pdo->lastInsertId()]); exit;
    }
    if ($a === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $first = trim($_POST['first'] ?? ''); $last = trim($_POST['last'] ?? '');
        $dep = (int) ($_POST['department_id'] ?? 0); $role = (int) ($_POST['role_id'] ?? 0);
        if ($id <= 0 || $first === '' || $last === '') { echo json_encode(['ok' => false, 'error' => 'Thiếu dữ liệu']); exit; }
        $st = $pdo->prepare("UPDATE employees SET first=:f,last=:l,department_id=:d,role_id=:r WHERE id=:id");
        $st->execute([':f' => $first, ':l' => $last, ':d' => $dep ?: null, ':r' => $role ?: null, ':id' => $id]);
        echo json_encode(['ok' => true]); exit;
    }
    if ($a === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) { echo json_encode(['ok' => false, 'error' => 'Thiếu id']); exit; }
        $st = $pdo->prepare("DELETE FROM employees WHERE id=:id");
        $st->execute([':id' => $id]);
        echo json_encode(['ok' => true]); exit;
    }
    echo json_encode(['ok' => false, 'error' => 'action?']); exit;
}

// SSR
$rows = $pdo->query("
    SELECT e.id, e.first, e.last, e.department_id, e.role_id,
           d.name AS department_name, r.name AS role_name
    FROM employees e
      LEFT JOIN departments d ON d.id=e.department_id
      LEFT JOIN roles       r ON r.id=e.role_id
    ORDER BY e.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
$deps = $pdo->query("SELECT id,name FROM departments ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$roles = $pdo->query("SELECT id,name FROM roles ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Employees</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body data-page="employees">
<div class="wrap">
    <?php include 'includes/navbar.php'; ?>
    <h2>Employees</h2>

    <button class="btn" onclick="openAdd('employees')" <?= is_admin() ? '' : 'disabled title="Chỉ Admin"' ?>>+ Thêm</button>

    <table class="table" id="tb">
        <thead><tr><th>ID</th><th>First</th><th>Last</th><th>Department</th><th>Role</th><th>Hành động</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr data-id="<?= $r['id'] ?>">
                <td><?= $r['id'] ?></td>
                <td class="c-first"><?= safe_html($r['first']) ?></td>
                <td class="c-last"><?= safe_html($r['last']) ?></td>
                <td class="c-dep" data-dep="<?= $r['department_id'] ?>"><?= safe_html($r['department_name']) ?></td>
                <td class="c-role" data-role="<?= $r['role_id'] ?>"><?= safe_html($r['role_name']) ?></td>
                <td>
                    <?php if (is_admin()): ?>
                        <button class="btn" onclick="openEdit(<?= $r['id'] ?>, '', 'employees')">Sửa</button>
                        <button class="btn" onclick="delRow(<?= $r['id'] ?>, 'listemployees.php')">Xóa</button>
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
    <h3>Thêm Employee</h3>
    <form id="fAdd" class="frm">
        <div class="row employee-row"><label>Họ</label><input name="last" required></div>
        <div class="row employee-row"><label>Tên</label><input name="first" required></div>
        <div class="row employee-row"><label>Department</label>
            <select name="department_id">
                <option value="">--None--</option>
                <?php foreach ($deps as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= safe_html($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="row employee-row"><label>Role</label>
            <select name="role_id">
                <option value="">--None--</option>
                <?php foreach ($roles as $r): ?>
                    <option value="<?= $r['id'] ?>"><?= safe_html($r['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display:flex;gap:8px;justify-content:flex-end">
            <button type="submit" class="btn">Lưu</button>
            <button type="button" class="btn" onclick="closeP('pAdd')">Hủy</button>
        </div>
    </form>
</div></div>

<div class="popup" id="pEdit"><div class="pbox">
    <h3>Sửa Employee</h3>
    <form id="fEdit" class="frm">
        <input type="hidden" name="id" id="eid">
        <div class="row employee-row"><label>Họ</label><input name="last" id="elast" required></div>
        <div class="row employee-row"><label>Tên</label><input name="first" id="efirst" required></div>
        <div class="row employee-row"><label>Department</label>
            <select name="department_id" id="edep">
                <option value="">--None--</option>
                <?php foreach ($deps as $d): ?>
                    <option value="<?= $d['id'] ?>"><?= safe_html($d['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="row employee-row"><label>Role</label>
            <select name="role_id" id="erole">
                <option value="">--None--</option>
                <?php foreach ($roles as $r): ?>
                    <option value="<?= $r['id'] ?>"><?= safe_html($r['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
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