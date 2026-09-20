<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0 || !$conn) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Job not found']);
    exit;
}

$stmt = $conn->prepare('SELECT j.*, c.name AS category_name FROM jobs j JOIN categories c ON c.id = j.category_id WHERE j.id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();

if (!$job) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Job not found']);
    exit;
}

$salary = $job['salary_min'] !== null || $job['salary_max'] !== null
    ? formatSalaryLPA($job['salary_min'] ?? 0, $job['salary_max'] ?? 0)
    : ($job['salary_range'] ?: 'Negotiable');

echo json_encode([
    'success' => true,
    'job' => [
        'id' => (int) $job['id'],
        'title' => $job['title'],
        'company' => $job['company_name'],
        'location' => $job['location'],
        'type' => $job['job_type'],
        'experience' => $job['experience_level'],
        'salary' => $salary,
        'salary_min' => (float) ($job['salary_min'] ?? 0),
        'salary_max' => (float) ($job['salary_max'] ?? 0),
        'description' => $job['description'],
        'requirements' => $job['requirements'],
        'skills' => $job['skills_required'],
        'category' => $job['category_name'],
    ]
], JSON_THROW_ON_ERROR);
