<?php
// Frontend/login.php
require_once '../Backend/common.php';

$pdo = connect_db();
ensure_tables($pdo);

// AJAX handler (giữ nguyên)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_POST['ajax']))) {
    header('Content-Type: application/json; charset=utf-8');
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';

    if ($u === '' || $p === '') { echo json_encode(['ok' => false, 'error' => 'Thiếu username/password']); exit; }

    $st = $pdo->prepare("SELECT id, username, pass_hash, auth FROM users WHERE username=:u LIMIT 1");
    $st->execute([':u' => $u]);
    $row = $st->fetch(PDO::FETCH_ASSOC);

    if (!$row || !password_verify($p, $row['pass_hash'])) {
        echo json_encode(['ok' => false, 'error' => 'Sai thông tin đăng nhập']); exit;
    }
    session_regenerate_id(true);
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = (int) $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['auth'] = $row['auth'];
    $_SESSION['last_activity'] = time();

    echo json_encode(['ok' => true, 'user' => ['username' => $row['username'], 'auth' => $row['auth']]]); exit;
}

// Đã login → index
if (!empty($_SESSION['logged_in'])) { header("Location: index.php"); exit; }
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body data-page="login">
<div class="box">
    <h2>Đăng nhập</h2>
    <div id="msg" class="msg"></div>
    <form id="f">
        <div class="row login-row"><label>Username</label><input name="username" required></div>
        <div class="row login-row"><label>Password</label><input type="password" name="password" required></div>
        <button type="submit">Đăng nhập</button>
        <div style="margin-top:6px;color:#666;font-size:12px">Mặc định seed lần đầu: <b>admin / admin</b></div>
    </form>
</div>
<script src="assets/script.js"></script>
</body>
</html>