<?php
/**
 * CareerConnect - AJAX / POST Job Application Submission Handler
 */
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

require_login();
$user_id = current_user_id();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/jobs.php");
    exit;
}

if (!verify_csrf_token()) {
    set_flash('error', 'Security token invalid.');
    header("Location: " . BASE_URL . "/jobs.php");
    exit;
}

$job_id       = (int)($_POST['job_id'] ?? 0);
$cover_letter = sanitize($_POST['cover_letter'] ?? '');

if ($job_id <= 0) {
    set_flash('error', 'Invalid job selected.');
    header("Location: " . BASE_URL . "/jobs.php");
    exit;
}

// Check duplicate application
$check = $conn->query("SELECT id FROM applications WHERE job_id = {$job_id} AND user_id = {$user_id}");
if ($check && $check->num_rows > 0) {
    set_flash('warning', 'You have already applied for this job.');
    header("Location: " . BASE_URL . "/job-details.php?id=" . $job_id);
    exit;
}

// Handle Resume Upload if provided
$resume_filename = 'resume_' . $user_id . '.pdf';
if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
    $file_tmp  = $_FILES['resume']['tmp_name'];
    $file_name = $_FILES['resume']['name'];
    $file_size = $_FILES['resume']['size'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($file_ext, ALLOWED_RESUME_EXT) && $file_size <= MAX_FILE_SIZE) {
        if (!file_exists(RESUME_UPLOAD_PATH)) {
            mkdir(RESUME_UPLOAD_PATH, 0777, true);
        }
        $new_name = 'app_resume_' . $user_id . '_' . time() . '.' . $file_ext;
        if (move_uploaded_file($file_tmp, RESUME_UPLOAD_PATH . '/' . $new_name)) {
            $resume_filename = $new_name;
        }
    }
}

// Insert Application Record
$stmt = $conn->prepare("
    INSERT INTO applications (job_id, user_id, cover_letter, resume_file, status) 
    VALUES (?, ?, ?, ?, 'Applied')
");
$stmt->bind_param("iiss", $job_id, $user_id, $cover_letter, $resume_filename);

if ($stmt->execute()) {
    set_flash('success', 'Application submitted successfully! Track your application status on your dashboard.');
    header("Location: " . BASE_URL . "/user/applications.php");
    exit;
} else {
    set_flash('error', 'Database error submitting application.');
    header("Location: " . BASE_URL . "/job-details.php?id=" . $job_id);
    exit;
}
