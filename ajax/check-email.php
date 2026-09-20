<?php
/**
 * CareerConnect - AJAX Real-Time Email Availability Checker
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$email = strtolower(sanitize($_GET['email'] ?? ''));

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$conn) {
    echo json_encode(['exists' => false]);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

echo json_encode(['exists' => ($res && $res->num_rows > 0)]);
