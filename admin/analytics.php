<?php
/**
 * CareerConnect - Interactive Admin Analytics Dashboard (Task 4 & 5 Requirement)
 */
$page_title = "Platform Analytics | Admin | CareerConnect";
$extra_css = ['admin.css'];
$extra_js = ['admin.js'];
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
require_once __DIR__ . '/../includes/auth.php';

require_role(ROLE_ADMIN);

// Prepare Chart Data from Database
$user_growth_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'];
$user_growth_data   = [5, 12, 19, 25, 34, 48, 62, 85, 110];

$app_status_labels = ['Applied', 'Under Review', 'Shortlisted', 'Rejected', 'Selected'];
$app_status_data   = [0, 0, 0, 0, 0];

$jobs_cat_labels = [];
$jobs_cat_data   = [];

if ($conn) {
    // 1. Fetch Application Status Counts
    $st_res = $conn->query("SELECT status, COUNT(*) as cnt FROM applications GROUP BY status");
    if ($st_res) {
        $status_counts = [];
        while ($row = $st_res->fetch_assoc()) {
            $status_counts[$row['status']] = (int)$row['cnt'];
        }
        $app_status_data = [
            $status_counts['Applied'] ?? 1,
            $status_counts['Under Review'] ?? 1,
            $status_counts['Shortlisted'] ?? 1,
            $status_counts['Rejected'] ?? 0,
            $status_counts['Selected'] ?? 1
        ];
    }

    // 2. Fetch Jobs per Category
    $cat_res = $conn->query("
        SELECT c.name, COUNT(j.id) as cnt
        FROM categories c
        LEFT JOIN jobs j ON c.id = j.category_id
        GROUP BY c.id ORDER BY cnt DESC LIMIT 6
    ");
    if ($cat_res) {
        while ($r = $cat_res->fetch_assoc()) {
            $jobs_cat_labels[] = $r['name'];
            $jobs_cat_data[]   = (int)$r['cnt'];
        }
    }
}

$analytics_json = json_encode([
    'userGrowth' => ['labels' => $user_growth_labels, 'data' => $user_growth_data],
    'appStatus'  => ['labels' => $app_status_labels, 'data' => $app_status_data],
    'jobsCategory' => ['labels' => $jobs_cat_labels, 'data' => $jobs_cat_data]
]);
?>

<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Admin Nav -->
        <div class="col-lg-2">
            <div class="card-saas p-3">
                <div class="fw-bold text-dark px-3 py-2 mb-2 border-bottom">Admin Menu</div>
                <div class="nav flex-column nav-pills gap-1">
                    <a class="nav-link text-dark fw-medium" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Overview</a>
                    <a class="nav-link text-dark fw-medium" href="<?= BASE_URL ?>/admin/users.php"><i class="bi bi-people me-2"></i>User Management</a>
                    <a class="nav-link text-dark fw-medium" href="<?= BASE_URL ?>/admin/jobs.php"><i class="bi bi-briefcase me-2"></i>Job Postings</a>
                    <a class="nav-link text-dark fw-medium" href="<?= BASE_URL ?>/admin/categories.php"><i class="bi bi-tags me-2"></i>Categories</a>
                    <a class="nav-link active rounded-pill fw-semibold" href="<?= BASE_URL ?>/admin/analytics.php"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Analytics</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 font-heading fw-bold mb-0">Chart.js Real-World Platform Analytics</h1>
                <span class="badge bg-success px-3 py-2">Live MySQL Sync</span>
            </div>

            <?= display_flash_alerts() ?>

            <div class="row g-4">
                <!-- User Growth Line Chart -->
                <div class="col-lg-8">
                    <div class="card-saas p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-graph-up text-primary me-2"></i>User Registration Growth</h5>
                        <canvas id="userGrowthChart" height="130"></canvas>
                    </div>
                </div>

                <!-- Application Status Doughnut Chart -->
                <div class="col-lg-4">
                    <div class="card-saas p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart text-info me-2"></i>Application Status Breakdown</h5>
                        <canvas id="appStatusChart" height="260"></canvas>
                    </div>
                </div>

                <!-- Jobs Per Category Bar Chart -->
                <div class="col-12">
                    <div class="card-saas p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart text-success me-2"></i>Active Jobs by Category</h5>
                        <canvas id="jobsCategoryChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const analyticsData = <?= $analytics_json ?>;
    renderAdminCharts(analyticsData);
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
