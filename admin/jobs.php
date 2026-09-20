<?php
/**
 * CareerConnect - Admin Job Moderation Page
 */
$page_title = "Moderate Jobs | Admin | CareerConnect";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
require_once __DIR__ . '/../includes/auth.php';

require_role(ROLE_ADMIN);

// Delete job action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $jid = (int)$_GET['id'];
    $conn->query("DELETE FROM jobs WHERE id = {$jid}");
    set_flash('success', 'Job posting removed by administrator.');
    header("Location: " . BASE_URL . "/admin/jobs.php");
    exit;
}

$jobs = [];
if ($conn) {
    $res = $conn->query("
        SELECT j.*, c.name as category_name, u.full_name as recruiter_name, COUNT(a.id) as app_count
        FROM jobs j
        JOIN categories c ON j.category_id = c.id
        JOIN users u ON j.recruiter_id = u.id
        LEFT JOIN applications a ON j.id = a.job_id
        GROUP BY j.id ORDER BY j.created_at DESC
    ");
    if ($res) $jobs = $res->fetch_all(MYSQLI_ASSOC);
}
?>

<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="card-saas p-3">
                <div class="fw-bold text-dark px-3 py-2 mb-2 border-bottom">Admin Menu</div>
                <div class="nav flex-column nav-pills gap-1">
                    <a class="nav-link text-dark fw-medium" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Overview</a>
                    <a class="nav-link text-dark fw-medium" href="<?= BASE_URL ?>/admin/users.php"><i class="bi bi-people me-2"></i>User Management</a>
                    <a class="nav-link active rounded-pill fw-semibold" href="<?= BASE_URL ?>/admin/jobs.php"><i class="bi bi-briefcase me-2"></i>Job Postings</a>
                    <a class="nav-link rounded-pill text-dark fw-medium" href="<?= BASE_URL ?>/admin/categories.php"><i class="bi bi-tags me-2"></i>Categories</a>
                    <a class="nav-link rounded-pill text-dark fw-medium" href="<?= BASE_URL ?>/admin/analytics.php"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Analytics</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <h1 class="h3 font-heading fw-bold mb-4">Job Postings Moderation</h1>
            
            <?= display_flash_alerts() ?>

            <div class="card-saas p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th>Job Title</th>
                                <th>Recruiter</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Applicants</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jobs as $j): ?>
                                <tr>
                                    <td>
                                        <strong class="text-dark d-block"><?= escape($j['title']) ?></strong>
                                        <small class="text-muted"><?= escape($j['company_name']) ?> &bull; <?= escape($j['location']) ?></small>
                                    </td>
                                    <td class="small"><?= escape($j['recruiter_name']) ?></td>
                                    <td class="small"><?= escape($j['category_name']) ?></td>
                                    <td><span class="badge <?= get_job_type_badge($j['job_type']) ?>"><?= escape($j['job_type']) ?></span></td>
                                    <td><span class="badge bg-secondary"><?= $j['app_count'] ?></span></td>
                                    <td class="text-end">
                                        <a href="<?= BASE_URL ?>/job-details.php?id=<?= $j['id'] ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-1" target="_blank">View</a>
                                        <a href="<?= BASE_URL ?>/admin/jobs.php?action=delete&id=<?= $j['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Delete this job listing?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
