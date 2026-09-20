<?php
/**
 * CareerConnect - Login Handler
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

if (!verify_csrf_token()) {
    set_flash('error', 'Security token mismatch. Please try again.');
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$identity = sanitize($_POST['login_identity'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($identity) || empty($password)) {
    set_flash('error', 'Please enter both your email/username and password.');
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

if (!$conn) {
    set_flash('error', 'Database connection unavailable.');
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$stmt = $conn->prepare("SELECT id, role_id, username, email, password_hash, status, is_verified, full_name FROM users WHERE email = ? OR username = ? LIMIT 1");
$stmt->bind_param('ss', $identity, $identity);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    set_flash('error', 'Invalid email or password.');
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

if (($user['status'] ?? '') === 'suspended') {
    set_flash('error', 'Your account has been suspended. Please contact support.');
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

if ((int) ($user['is_verified'] ?? 0) !== 1) {
    set_flash('error', 'Please verify your email before logging in.');
    $_SESSION['pending_otp_email'] = $user['email'];
    $_SESSION['pending_user_id'] = $user['id'];
    header('Location: ' . BASE_URL . '/verify-otp.php');
    exit;
}

if (!password_verify($password, $user['password_hash'])) {
    set_flash('error', 'Invalid email or password.');
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['email'] = $user['email'];
$_SESSION['role_id'] = (int) $user['role_id'];
$_SESSION['role'] = $user['role_id'] == ROLE_ADMIN ? 'ADMIN' : ($user['role_id'] == ROLE_RECRUITER ? 'RECRUITER' : 'USER');
$_SESSION['logged_in'] = true;
$_SESSION['username'] = $user['username'];
$_SESSION['full_name'] = $user['full_name'];
set_flash('success', 'Welcome back, ' . $user['full_name'] . '!');

if ($user['role_id'] == ROLE_ADMIN) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}
if ($user['role_id'] == ROLE_RECRUITER) {
    header('Location: ' . BASE_URL . '/recruiter/dashboard.php');
    exit;
}
header('Location: ' . BASE_URL . '/user/dashboard.php');
exit;
