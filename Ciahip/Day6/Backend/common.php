<?php
// backend/common.php
// Gom auth.php, createtable.php, và pdo_config.php (giả sử connect_db() ở đây).

require_once '../../config/pdo_config.php';
session_start();

// -------------------------------------
// 1️⃣ HÀM KHỞI TẠO CSDL
// -------------------------------------
function ensure_tables(PDO $pdo): void {
    // --- Departments ---
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS departments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // --- Roles ---
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // --- Employees ---
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS employees (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first VARCHAR(100) NOT NULL,
            last  VARCHAR(100) NOT NULL,
            department_id INT NULL,
            role_id INT NULL,
            FOREIGN KEY (department_id) REFERENCES departments(id)
                ON UPDATE CASCADE ON DELETE SET NULL,
            FOREIGN KEY (role_id) REFERENCES roles(id)
                ON UPDATE CASCADE ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // --- Users ---
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username  VARCHAR(50) NOT NULL UNIQUE,
            pass_hash VARCHAR(255) NOT NULL,
            auth ENUM('Admin','User') NOT NULL DEFAULT 'User',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // --- Seed admin nếu bảng rỗng ---
    $cnt = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($cnt === 0) {
        $hash = password_hash('admin', PASSWORD_DEFAULT);
        $st = $pdo->prepare("INSERT INTO users(username, pass_hash, auth) VALUES(:u,:p,'Admin')");
        $st->execute([':u'=>'admin', ':p'=>$hash]);
    }
}

// -------------------------------------
// 2️⃣ HÀM XỬ LÝ PHIÊN
// -------------------------------------
$__SESSION_TIMEOUT_SECONDS = 60 * 10;

function __touch_session_timeout(): void {
    global $__SESSION_TIMEOUT_SECONDS;
    if (!empty($_SESSION['logged_in'])) {
        if (isset($_SESSION['last_activity']) &&
            (time() - $_SESSION['last_activity'] > $__SESSION_TIMEOUT_SECONDS)) {
            session_unset();
            session_destroy();
            header("Location: login.php?expired=1");
            exit;
        }
        $_SESSION['last_activity'] = time();
    }
}
__touch_session_timeout();

// -------------------------------------
// 3️⃣ CÁC HÀM XÁC THỰC
// -------------------------------------
function require_login(): void {
    if (empty($_SESSION['logged_in'])) {
        header("Location: login.php");
        exit;
    }
}

function current_user(): array {
    return [
        'id'       => $_SESSION['user_id']   ?? null,
        'username' => $_SESSION['username']  ?? null,
        'auth'     => $_SESSION['auth']      ?? null,
    ];
}

function is_admin(): bool {
    return !empty($_SESSION['auth']) && $_SESSION['auth'] === 'Admin';
}

function require_admin(): void {
    if (!is_admin()) {
        header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
        echo "403 Forbidden";
        exit;
    }
}

function json_forbidden(): void {
    header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok'=>false, 'error'=>'forbidden']);
    exit;
}

// -------------------------------------
// 4️⃣ CÔNG CỤ BỔ TRỢ
// -------------------------------------
function safe_html(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Flash messages
function set_flash(string $msg): void {
    $_SESSION['__flash'] = $msg;
}
function get_flash(): string {
    if (!empty($_SESSION['__flash'])) {
        $m = $_SESSION['__flash'];
        unset($_SESSION['__flash']);
        return $m;
    }
    return '';
}

// -------------------------------------
// 5️⃣ HÀM TIỆN ÍCH CÓ PDO
// -------------------------------------
function find_user_by_username(PDO $pdo, string $username): ?array {
    $st = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $st->execute([$username]);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

function create_user(PDO $pdo, string $username, string $password, string $auth='User'): bool {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $st = $pdo->prepare("INSERT INTO users(username, pass_hash, auth) VALUES(?,?,?)");
    try {
        return $st->execute([$username, $hash, $auth]);
    } catch (PDOException $e) {
        return false;
    }
}

function verify_login(PDO $pdo, string $username, string $password): bool {
    $user = find_user_by_username($pdo, $username);
    if (!$user) return false;
    if (!password_verify($password, $user['pass_hash'])) return false;

    // Đăng nhập thành công
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['auth']      = $user['auth'];
    $_SESSION['last_activity'] = time();
    return true;
}

function logout_user(): void {
    session_unset();
    session_destroy();
}
