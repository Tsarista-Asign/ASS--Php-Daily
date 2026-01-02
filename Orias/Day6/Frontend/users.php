<?php
// Frontend/users.php
require_once '../Backend/common.php';

require_login();
require_admin();
$pdo = connect_db();
ensure_tables($pdo);

// AJAX (giữ nguyên)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_POST['ajax']))) {
    header('Content-Type: application/json; charset=utf-8');
    $a = $_POST['action'] ?? '';

    if ($a === 'list') {
        $rows = $pdo->query("SELECT id, username, auth, created_at FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['ok' => true, 'data' => $rows]); exit;
    }
    if ($a === 'add') {
        $u = trim($_POST['username'] ?? ''); $p = $_POST['password'] ?? ''; $auth = $_POST['auth'] ?? 'User';
        if ($u === '' || $p === '') { echo json_encode(['ok' => false, 'error' => 'Thiếu dữ liệu']); exit; }
        if (!in_array($auth, ['Admin', 'User'], true)) $auth = 'User';
        $hash = password_hash($p, PASSWORD_DEFAULT);
        try {
            $st = $pdo->prepare("INSERT INTO users(username, pass_hash, auth) VALUES(:u,:h,:a)");
            $st->execute([':u' => $u, ':h' => $hash, ':a' => $auth]);
            echo json_encode(['ok' => true, 'id' => $pdo->lastInsertId()]); exit;
        } catch (PDOException $e) {
            echo json_encode(['ok' => false, 'error' => 'Username đã tồn tại']); exit;
        }
    }
    if ($a === 'set_auth') {
        $id = (int) ($_POST['id'] ?? 0); $auth = $_POST['auth'] ?? 'User';
        if ($id <= 0) { echo json_encode(['ok' => false, 'error' => 'Thiếu id']); exit; }
        if (!in_array($auth, ['Admin', 'User'], true)) { echo json_encode(['ok' => false, 'error' => 'auth không hợp lệ']); exit; }
        $st = $pdo->prepare("UPDATE users SET auth=:a WHERE id=:id");
        $st->execute([':a' => $auth, ':id' => $id]);
        echo json_encode(['ok' => true]); exit;
    }
    if ($a === 'reset_pass') {
        $id = (int) ($_POST['id'] ?? 0); $p = $_POST['password'] ?? '';
        if ($id <= 0 || $p === '') { echo json_encode(['ok' => false, 'error' => 'Thiếu dữ liệu']); exit; }
        $hash = password_hash($p, PASSWORD_DEFAULT);
        $st = $pdo->prepare("UPDATE users SET pass_hash=:h WHERE id=:id");
        $st->execute([':h' => $hash, ':id' => $id]);
        echo json_encode(['ok' => true]); exit;
    }
    if ($a === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) { echo json_encode(['ok' => false, 'error' => 'Thiếu id']); exit; }
        if (!empty($_SESSION['user_id']) && $id === (int) $_SESSION['user_id']) {
            echo json_encode(['ok' => false, 'error' => 'Không thể xóa chính mình']); exit;
        }
        $st = $pdo->prepare("DELETE FROM users WHERE id=:id");
        $st->execute([':id' => $id]);
        echo json_encode(['ok' => true]); exit;
    }
    echo json_encode(['ok' => false, 'error' => 'action?']); exit;
}

// SSR
$rows = $pdo->query("SELECT id, username, auth, created_at FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Users (Admin)</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body data-page="users">
<div class="wrap">
    <?php include 'includes/navbar.php'; ?>
    <h2>Quản trị Users</h2>

    <button class="btn" onclick="openAdd('users')">+ Thêm user</button>

    <table class="table" id="tb">
        <thead><tr><th>ID</th><th>Username</th><th>Auth</th><th>Tạo lúc</th><th>Hành động</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr data-id="<?= $r['id'] ?>" data-un="<?= safe_html($r['username']) ?>">
                <td><?= $r['id'] ?></td>
                <td><?= safe_html($r['username']) ?></td>
                <td class="c-auth"><?= safe_html($r['auth']) ?></td>
                <td><?= safe_html($r['created_at']) ?></td>
                <td>
                    <select onchange="setAuth(<?= $r['id'] ?>, this.value)">
                        <option value="User" <?= $r['auth'] === 'User' ? 'selected' : '' ?>>User</option>
                        <option value="Admin" <?= $r['auth'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <button class="btn" onclick="openReset(<?= $r['id'] ?>)">Đặt lại mật khẩu</button>
                    <button class="btn" onclick="delUser(<?= $r['id'] ?>)">Xóa</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="popup" id="pAdd"><div class="pbox">
    <h3>Thêm User</h3>
    <form id="fAdd">
        <div class="row"><label>Username</label><input name="username" required></div>
        <div class="row"><label>Password</label><input type="password" name="password" required></div>
        <div class="row"><label>Auth</label>
            <select name="auth"><option>User</option><option>Admin</option></select>
        </div>
        <div style="display:flex;gap:8px;justify-content:flex-end">
            <button class="btn" type="submit">Tạo</button>
            <button class="btn" type="button" onclick="closeP('pAdd')">Hủy</button>
        </div>
    </form>
</div></div>

<div class="popup" id="pReset"><div class="pbox">
    <h3>Đặt lại mật khẩu</h3>
    <form id="fReset">
        <input type="hidden" name="id" id="rid">
        <div class="row"><label>User</label><input id="rname" disabled></div>
        <div class="row"><label>Mật khẩu mới</label><input type="password" name="password" required></div>
        <div style="display:flex;gap:8px;justify-content:flex-end">
            <button class="btn" type="submit">Cập nhật</button>
            <button class="btn" type="button" onclick="closeP('pReset')">Hủy</button>
        </div>
    </form>
</div></div>

<script>const isAdmin = <?= is_admin() ? 'true' : 'false' ?>;</script>
<script src="assets/script.js"></script>
</body>
</html>