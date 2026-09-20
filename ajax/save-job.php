<?php
/**
 * CareerConnect - AJAX Bookmark / Save Job Handler
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Please sign in to save jobs.']);
    exit;
}

$user_id = current_user_id();
$job_id  = (int)($_POST['job_id'] ?? 0);

if ($job_id <= 0 || !$conn) {
    echo json_encode(['success' => false, 'message' => 'Invalid job selected.']);
    exit;
}

// Check existing save
$check = $conn->query("SELECT id FROM saved_jobs WHERE user_id = {$user_id} AND job_id = {$job_id}");

if ($check && $check->num_rows > 0) {
    // Unsave
    $conn->query("DELETE FROM saved_jobs WHERE user_id = {$user_id} AND job_id = {$job_id}");
    echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Job removed from saved list.']);
} else {
    // Save
    $stmt = $conn->prepare("INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $job_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'action' => 'saved', 'message' => 'Job bookmarked successfully!']);
}
