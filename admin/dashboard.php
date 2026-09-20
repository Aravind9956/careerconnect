<?php
/**
 * CareerConnect - Admin Dashboard Overview (Task 4 & 5 Requirement)
 */
$page_title = "Admin Dashboard | CareerConnect";
$extra_css = ['admin.css'];
$extra_js = ['admin.js'];
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
require_once __DIR__ . '/../includes/auth.php';

require_role(ROLE_ADMIN);

// System Metrics
$cnt_seekers = 0;
$cnt_recruiters = 0;
$cnt_jobs = 0;
$cnt_applications = 0;

if ($conn) {
    $cnt_seekers = $conn->query("SELECT COUNT(*) as c FROM users WHERE role_id = 3")->fetch_assoc()['c'];
    $cnt_recruiters = $conn->query("SELECT COUNT(*) as c FROM users WHERE role_id = 2")->fetch_assoc()['c'];
    $cnt_jobs = $conn->query("SELECT COUNT(*) as c FROM jobs")->fetch_assoc()['c'];
    $cnt_applications = $conn->query("SELECT COUNT(*) as c FROM applications")->fetch_assoc()['c'];
}
?>

<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Admin Sidebar Nav -->
        <div class="col-lg-2">
            <div class="card-saas p-3">
                <div class="fw-bold text-dark px-3 py-2 mb-2 border-bottom">Admin Menu</div>
                <div class="nav flex-column nav-pills gap-1">
                    <a class="nav-link active rounded-pill fw-semibold" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Overview</a>
                    <a class="nav-link rounded-pill text-dark fw-medium" href="<?= BASE_URL ?>/admin/users.php"><i class="bi bi-people me-2"></i>User Management</a>
                    <a class="nav-link rounded-pill text-dark fw-medium" href="<?= BASE_URL ?>/admin/jobs.php"><i class="bi bi-briefcase me-2"></i>Job Postings</a>
                    <a class="nav-link rounded-pill text-dark fw-medium" href="<?= BASE_URL ?>/admin/categories.php"><i class="bi bi-tags me-2"></i>Categories</a>
                    <a class="nav-link rounded-pill text-dark fw-medium" href="<?= BASE_URL ?>/admin/analytics.php"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Analytics</a>
                </div>
            </div>
        </div>

        <!-- Admin Main Content -->
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 font-heading fw-bold mb-0">System Control & Analytics</h1>
                <a href="<?= BASE_URL ?>/admin/analytics.php" class="btn btn-primary rounded-pill px-4 btn-sm fw-semibold">
                    <i class="bi bi-pie-chart me-1"></i>Detailed Analytics
                </a>
            </div>

            <?= display_flash_alerts() ?>

            <!-- Metrics Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-sm-6">
                    <div class="dashboard-stat-card">
                        <div>
                            <h3 class="fw-bold text-dark mb-0"><?= number_format($cnt_seekers) ?></h3>
                            <span class="text-muted small">Job Seekers</span>
                        </div>
                        <div class="dashboard-stat-icon bg-primary-light text-primary"><i class="bi bi-person font-bold"></i></div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="dashboard-stat-card">
                        <div>
                            <h3 class="fw-bold text-info mb-0"><?= number_format($cnt_recruiters) ?></h3>
                            <span class="text-muted small">Employers</span>
                        </div>
                        <div class="dashboard-stat-icon bg-info-subtle text-info"><i class="bi bi-building"></i></div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="dashboard-stat-card">
                        <div>
                            <h3 class="fw-bold text-success mb-0"><?= number_format($cnt_jobs) ?></h3>
                            <span class="text-muted small">Total Jobs</span>
                        </div>
                        <div class="dashboard-stat-icon bg-success-subtle text-success"><i class="bi bi-briefcase"></i></div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="dashboard-stat-card">
                        <div>
                            <h3 class="fw-bold text-warning mb-0"><?= number_format($cnt_applications) ?></h3>
                            <span class="text-muted small">Applications</span>
                        </div>
                        <div class="dashboard-stat-icon bg-warning-subtle text-warning"><i class="bi bi-file-earmark-text"></i></div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Analytics Preview Grid -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card-saas p-4">
                        <h5 class="fw-bold mb-3">Platform Activity</h5>
                        <p class="text-muted small mb-0">Visit the <a href="<?= BASE_URL ?>/admin/analytics.php" class="text-primary fw-semibold">Analytics Dashboard</a> to view real-time Chart.js growth charts and application status breakdowns.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-saas p-4">
                        <h5 class="fw-bold mb-3">Quick Management</h5>
                        <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                            <li><a href="<?= BASE_URL ?>/admin/users.php" class="btn btn-light w-100 text-start py-2"><i class="bi bi-people me-2 text-primary"></i>Manage All Users</a></li>
                            <li><a href="<?= BASE_URL ?>/admin/jobs.php" class="btn btn-light w-100 text-start py-2"><i class="bi bi-briefcase me-2 text-success"></i>Moderate Job Postings</a></li>
                            <li><a href="<?= BASE_URL ?>/admin/categories.php" class="btn btn-light w-100 text-start py-2"><i class="bi bi-tags me-2 text-info"></i>Job Categories</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
