<?php
/**
 * CareerConnect - OTP Verification & Resend Handler
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/Mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/verify-otp.php');
    exit;
}

if (!verify_csrf_token()) {
    set_flash('error', 'Security token invalid.');
    header('Location: ' . BASE_URL . '/verify-otp.php');
    exit;
}

$email = $_SESSION['pending_otp_email'] ?? '';
if (empty($email)) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$action = $_POST['action'] ?? 'verify';

if ($action === 'resend') {
    $userId = $_SESSION['pending_user_id'] ?? null;
    $newOtp = (string) random_int(100000, 999999);
    $newHash = password_hash($newOtp, PASSWORD_DEFAULT);
    $expires = date('Y-m-d H:i:s', strtotime('+5 minutes'));

    $oldStmt = $conn->prepare('UPDATE otp_verifications SET expires_at = NOW() WHERE email = ? AND purpose = "registration" AND verified_at IS NULL');
    $oldStmt->bind_param('s', $email);
    $oldStmt->execute();

    $stmt = $conn->prepare('INSERT INTO otp_verifications (user_id, email, otp_hash, purpose, expires_at, attempts, verified_at, created_at) VALUES (?, ?, ?, "registration", ?, 0, NULL, NOW())');
    $stmt->bind_param('isss', $userId, $email, $newHash, $expires);
    $stmt->execute();

    $name = $_SESSION['full_name'] ?? 'User';
    sendOtpEmail($email, $name, $newOtp);
    $_SESSION['simulated_otp_code'] = $newOtp;
    set_flash('success', 'A new 6-digit OTP code has been sent to your email.');
    header('Location: ' . BASE_URL . '/verify-otp.php');
    exit;
}

$enteredOtp = sanitize($_POST['otp_code'] ?? '');
if (empty($enteredOtp)) {
    set_flash('error', 'Please enter the 6-digit OTP code.');
    header('Location: ' . BASE_URL . '/verify-otp.php');
    exit;
}

$stmt = $conn->prepare('SELECT id, user_id, otp_hash, expires_at, attempts FROM otp_verifications WHERE email = ? AND purpose = "registration" AND verified_at IS NULL ORDER BY created_at DESC LIMIT 1');
if (!$stmt) {
    throw new RuntimeException('Failed to prepare OTP lookup: ' . $conn->error);
}
$stmt->bind_param('s', $email);
$stmt->execute();
$otpRow = $stmt->get_result()->fetch_assoc();

if (!$otpRow) {
    set_flash('error', 'Invalid or expired OTP code. Please request a new one.');
    header('Location: ' . BASE_URL . '/verify-otp.php');
    exit;
}

if (strtotime($otpRow['expires_at']) < time()) {
    set_flash('error', 'OTP expired. Please request a new OTP.');
    header('Location: ' . BASE_URL . '/verify-otp.php');
    exit;
}

if ((int) $otpRow['attempts'] >= 5) {
    set_flash('error', 'Too many failed OTP attempts. Please request a new OTP.');
    header('Location: ' . BASE_URL . '/verify-otp.php');
    exit;
}

if (!password_verify($enteredOtp, $otpRow['otp_hash'])) {
    $attempts = (int) $otpRow['attempts'] + 1;
    $update = $conn->prepare('UPDATE otp_verifications SET attempts = ? WHERE id = ?');
    $update->bind_param('ii', $attempts, $otpRow['id']);
    $update->execute();
    set_flash('error', 'Invalid OTP code. Please try again.');
    header('Location: ' . BASE_URL . '/verify-otp.php');
    exit;
}

$conn->prepare('UPDATE otp_verifications SET verified_at = NOW(), attempts = attempts WHERE id = ?')->bind_param('i', $otpRow['id']);
$conn->prepare('UPDATE users SET is_verified = 1 WHERE email = ?')->bind_param('s', $email);

$updateOtp = $conn->prepare('UPDATE otp_verifications SET verified_at = NOW() WHERE id = ?');
$updateOtp->bind_param('i', $otpRow['id']);
$updateOtp->execute();

$updateUser = $conn->prepare('UPDATE users SET is_verified = 1 WHERE email = ?');
$updateUser->bind_param('s', $email);
$updateUser->execute();

$userStmt = $conn->prepare('SELECT id, role_id, full_name, username FROM users WHERE email = ? LIMIT 1');
$userStmt->bind_param('s', $email);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['role_id'] = (int) $user['role_id'];
$_SESSION['logged_in'] = true;
$_SESSION['username'] = $user['username'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['email'] = $email;
unset($_SESSION['pending_otp_email'], $_SESSION['pending_user_id'], $_SESSION['simulated_otp_code']);

set_flash('success', 'Email verified successfully. You can now log in.');
header('Location: ' . BASE_URL . '/login.php');
exit;
