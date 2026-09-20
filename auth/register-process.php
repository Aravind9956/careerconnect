<?php
/**
 * CareerConnect - Registration Processor with OTP Generation
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/Mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/register.php');
    exit;
}

if (!verify_csrf_token()) {
    set_flash('error', 'Security token expired. Please try again.');
    header('Location: ' . BASE_URL . '/register.php');
    exit;
}

$role_id = (int) ($_POST['role_id'] ?? ROLE_USER);
if (!in_array($role_id, [ROLE_USER, ROLE_RECRUITER], true)) {
    $role_id = ROLE_USER;
}

$full_name = sanitize($_POST['full_name'] ?? '');
$username = strtolower(sanitize($_POST['username'] ?? ''));
$email = strtolower(sanitize($_POST['email'] ?? ''));
$phone = sanitize($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (empty($full_name) || empty($username) || empty($email) || empty($password)) {
    set_flash('error', 'Please fill in all required fields.');
    header('Location: ' . BASE_URL . '/register.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error', 'Invalid email address format.');
    header('Location: ' . BASE_URL . '/register.php');
    exit;
}

if (!preg_match('/^[0-9+()\-\s]{8,20}$/', $phone)) {
    set_flash('error', 'Please enter a valid phone number.');
    header('Location: ' . BASE_URL . '/register.php');
    exit;
}

if ($password !== $confirm) {
    set_flash('error', 'Passwords do not match.');
    header('Location: ' . BASE_URL . '/register.php');
    exit;
}

if (strlen($password) < 8) {
    set_flash('error', 'Password must be at least 8 characters long.');
    header('Location: ' . BASE_URL . '/register.php');
    exit;
}

if (!$conn) {
    set_flash('error', 'Database connection error.');
    header('Location: ' . BASE_URL . '/register.php');
    exit;
}

$check_stmt = $conn->prepare('SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1');
$check_stmt->bind_param('ss', $email, $username);
$check_stmt->execute();
if ($check_stmt->get_result()->num_rows > 0) {
    set_flash('error', 'An account with this email or username already exists.');
    header('Location: ' . BASE_URL . '/register.php');
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);
$insert_user = $conn->prepare('INSERT INTO users (role_id, username, email, password_hash, full_name, phone, status, is_verified) VALUES (?, ?, ?, ?, ?, ?, "active", 0)');
$insert_user->bind_param('isssss', $role_id, $username, $email, $password_hash, $full_name, $phone);

if (!$insert_user->execute()) {
    set_flash('error', 'Registration failed due to a database error.');
    header('Location: ' . BASE_URL . '/register.php');
    exit;
}

$user_id = $conn->insert_id;
$insert_profile = $conn->prepare('INSERT INTO user_profiles (user_id) VALUES (?)');
if (!$insert_profile) {
    throw new RuntimeException('Failed to prepare user_profiles insert: ' . $conn->error);
}
$insert_profile->bind_param('i', $user_id);
$insert_profile->execute();

$otpCode = (string) random_int(100000, 999999);
$otpHash = password_hash($otpCode, PASSWORD_DEFAULT);
$expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));
$insertOtp = $conn->prepare('INSERT INTO otp_verifications (user_id, email, otp_hash, purpose, expires_at, attempts, verified_at, created_at) VALUES (?, ?, ?, "registration", ?, 0, NULL, NOW())');
if (!$insertOtp) {
    throw new RuntimeException('Failed to prepare otp_verifications insert: ' . $conn->error);
}
$insertOtp->bind_param('isss', $user_id, $email, $otpHash, $expiresAt);
$insertOtp->execute();

$_SESSION['pending_otp_email'] = $email;
$_SESSION['pending_user_id'] = $user_id;
$_SESSION['simulated_otp_code'] = $otpCode;

$sent = sendOtpEmail($email, $full_name, $otpCode);
if (!$sent) {
    error_log('OTP email could not be sent to ' . $email);
}

set_flash('success', 'Account created! Please verify your email using the 6-digit OTP code.');
header('Location: ' . BASE_URL . '/verify-otp.php');
exit;
