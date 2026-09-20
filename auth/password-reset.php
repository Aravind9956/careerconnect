<?php
/**
 * CareerConnect - Password Reset Step Processor
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/forgot-password.php");
    exit;
}

if (!verify_csrf_token()) {
    set_flash('error', 'Security token invalid.');
    header("Location: " . BASE_URL . "/forgot-password.php");
    exit;
}

$step = $_POST['step'] ?? '1';

if ($step === '1') {
    $email = strtolower(sanitize($_POST['email'] ?? ''));
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        $otp = (string)rand(100000, 999999);
        $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $otp_stmt = $conn->prepare("INSERT INTO otp_verifications (email, otp_code, type, expires_at) VALUES (?, ?, 'password_reset', ?)");
        $otp_stmt->bind_param("sss", $email, $otp, $expires);
        $otp_stmt->execute();

        $_SESSION['reset_email'] = $email;
        $_SESSION['simulated_reset_otp'] = $otp;

        set_flash('success', 'Reset OTP code generated! Please enter the code below.');
        header("Location: " . BASE_URL . "/forgot-password.php?step=2");
        exit;
    } else {
        set_flash('error', 'No user found with that email address.');
        header("Location: " . BASE_URL . "/forgot-password.php");
        exit;
    }
} elseif ($step === '2') {
    $email = $_SESSION['reset_email'] ?? '';
    $otp = sanitize($_POST['otp_code'] ?? '');
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_new_password'] ?? '';

    if (empty($email) || empty($otp) || empty($new_pass)) {
        set_flash('error', 'All fields are required.');
        header("Location: " . BASE_URL . "/forgot-password.php?step=2");
        exit;
    }

    if ($new_pass !== $confirm_pass) {
        set_flash('error', 'Passwords do not match.');
        header("Location: " . BASE_URL . "/forgot-password.php?step=2");
        exit;
    }

    // Verify OTP
    $stmt = $conn->prepare("
        SELECT id FROM otp_verifications 
        WHERE email = ? AND otp_code = ? AND type = 'password_reset' AND is_used = 0 AND expires_at >= NOW()
        ORDER BY id DESC LIMIT 1
    ");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        $otp_row = $res->fetch_assoc();

        // Mark OTP as used
        $conn->query("UPDATE otp_verifications SET is_used = 1 WHERE id = {$otp_row['id']}");

        // Update Password
        $new_hash = password_hash($new_pass, PASSWORD_BCRYPT);
        $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        $update_stmt->bind_param("ss", $new_hash, $email);
        $update_stmt->execute();

        unset($_SESSION['reset_email']);
        unset($_SESSION['simulated_reset_otp']);

        set_flash('success', 'Password updated successfully! You can now log in with your new password.');
        header("Location: " . BASE_URL . "/login.php");
        exit;
    } else {
        set_flash('error', 'Invalid or expired OTP code.');
        header("Location: " . BASE_URL . "/forgot-password.php?step=2");
        exit;
    }
}
