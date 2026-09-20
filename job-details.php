<?php
/**
 * CareerConnect - Job Details View Page
 */
$page_title = "Job Details | CareerConnect";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/csrf.php';

$job_id = (int)($_GET['id'] ?? 0);
$job = null;
$has_applied = false;
$is_saved = false;

if ($conn && $job_id > 0) {
    // Increment view count
    $conn->query("UPDATE jobs SET views_count = views_count + 1 WHERE id = {$job_id}");

    $stmt = $conn->prepare("
        SELECT j.*, c.name as category_name, u.email as recruiter_email
        FROM jobs j
        JOIN categories c ON j.category_id = c.id
        JOIN users u ON j.recruiter_id = u.id
        WHERE j.id = ? LIMIT 1
    ");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $job = $stmt->get_result()->fetch_assoc();

    // Check application & save status if logged in
    if ($job && is_logged_in()) {
        $uid = current_user_id();
        $app_check = $conn->query("SELECT id FROM applications WHERE job_id = {$job_id} AND user_id = {$uid}");
        $has_applied = ($app_check && $app_check->num_rows > 0);

        $save_check = $conn->query("SELECT id FROM saved_jobs WHERE job_id = {$job_id} AND user_id = {$uid}");
        $is_saved = ($save_check && $save_check->num_rows > 0);
    }
}

if (!$job) {
    header("Location: " . BASE_URL . "/404.php");
    exit;
}
?>

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/jobs.php">Jobs</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= escape($job['title']) ?></li>
        </ol>
    </nav>

    <?= display_flash_alerts() ?>

    <div class="row g-4">
        <!-- Main Job Info Column -->
        <div class="col-lg-8">
            <div class="card-saas p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge <?= get_job_type_badge($job['job_type']) ?> fs-6"><?= escape($job['job_type']) ?></span>
                    <span class="text-muted small"><i class="bi bi-eye me-1"></i><?= $job['views_count'] ?> views</span>
                </div>

                <h1 class="h3 font-heading fw-bold text-dark mb-2"><?= escape($job['title']) ?></h1>
                <p class="text-primary fw-semibold fs-5 mb-2"><i class="bi bi-building me-1"></i><?= escape($job['company_name']) ?></p>
                
                <div class="d-flex flex-wrap gap-3 text-muted small mb-4">
                    <span><i class="bi bi-geo-alt text-danger me-1"></i><?= escape($job['location']) ?></span>
                    <span><i class="bi bi-cash-stack text-success me-1"></i><?= escape(formatJobSalary($job)) ?></span>
                    <span><i class="bi bi-clock me-1"></i>Posted <?= time_ago($job['created_at']) ?></span>
                </div>

                <hr>

                <!-- Job Description -->
                <h5 class="fw-bold mb-3">Job Description</h5>
                <div class="text-secondary lh-lg mb-4">
                    <?= nl2br(escape($job['description'])) ?>
                </div>

                <!-- Job Requirements -->
                <?php if (!empty($job['requirements'])): ?>
                    <h5 class="fw-bold mb-3">Key Requirements</h5>
                    <div class="text-secondary lh-lg mb-4">
                        <?= nl2br(escape($job['requirements'])) ?>
                    </div>
                <?php endif; ?>

                <!-- Required Skills -->
                <?php if (!empty($job['skills_required'])): ?>
                    <h5 class="fw-bold mb-3">Skills Required</h5>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <?php foreach (explode(',', $job['skills_required']) as $skill): ?>
                            <span class="badge bg-light text-dark border px-3 py-2 fw-medium"><?= escape(trim($skill)) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <hr>

                <!-- Apply Button Action Bar -->
                <div class="d-flex align-items-center justify-content-between pt-2">
                    <?php if (is_logged_in()): ?>
                        <?php if (has_role(ROLE_USER)): ?>
                            <?php if ($has_applied): ?>
                                <button class="btn btn-success disabled rounded-pill px-4" disabled><i class="bi bi-check-circle me-1"></i>Already Applied</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#applyModal">
                                    <i class="bi bi-send me-1"></i>Apply Now
                                </button>
                            <?php endif; ?>

                            <button class="btn btn-outline-danger btn-save-job rounded-pill px-3" data-job-id="<?= $job['id'] ?>">
                                <i class="bi <?= $is_saved ? 'bi-bookmark-fill' : 'bi-bookmark' ?> me-1"></i><?= $is_saved ? 'Saved' : 'Save Job' ?>
                            </button>
                        <?php else: ?>
                            <div class="alert alert-info py-2 px-3 mb-0 small"><i class="bi bi-info-circle me-1"></i>Recruiters/Admins cannot submit job applications.</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login.php" class="btn btn-primary rounded-pill px-4">Sign in to Apply</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-4">
            <div class="card-saas p-4 mb-4">
                <h5 class="fw-bold mb-3">Overview</h5>
                <ul class="list-unstyled d-flex flex-column gap-3 text-secondary small mb-0">
                    <li class="d-flex justify-content-between">
                        <span>Category:</span>
                        <strong class="text-dark"><?= escape($job['category_name']) ?></strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Experience Level:</span>
                        <strong class="text-dark"><?= escape($job['experience_level']) ?> Level</strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Job Type:</span>
                        <strong class="text-dark"><?= escape($job['job_type']) ?></strong>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span>Salary:</span>
                        <strong class="text-dark"><?= escape(formatJobSalary($job)) ?></strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Application Modal -->
<?php if (is_logged_in() && has_role(ROLE_USER) && !$has_applied): ?>
<div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="applyModalLabel">Submit Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>/ajax/apply-job.php" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                <div class="modal-body py-3">
                    <p class="text-muted small">Applying for <strong><?= escape($job['title']) ?></strong> at <strong><?= escape($job['company_name']) ?></strong>.</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Cover Letter / Note to Recruiter</label>
                        <textarea name="cover_letter" class="form-control" rows="4" placeholder="Briefly introduce yourself and why you're a fit..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Upload Resume (PDF/DOC, Max 5MB)</label>
                        <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
