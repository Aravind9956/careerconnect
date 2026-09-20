<?php
/**
 * CareerConnect - AJAX Filter & Paginate Jobs Endpoint (Task 4 & 5 Requirement)
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$q          = sanitize($_GET['q'] ?? '');
$category   = (int)($_GET['category'] ?? 0);
$type       = sanitize($_GET['type'] ?? '');
$experience = sanitize($_GET['experience'] ?? '');
$page       = max(1, (int)($_GET['page'] ?? 1));
$limit      = 6;
$offset     = ($page - 1) * $limit;

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection offline.']);
    exit;
}

$where_clauses = ["j.status = 'active'"];
$params = [];
$types  = "";

if (!empty($q)) {
    $where_clauses[] = "(j.title LIKE ? OR j.company_name LIKE ? OR j.skills_required LIKE ? OR j.location LIKE ?)";
    $like_q = "%{$q}%";
    $params = array_merge($params, [$like_q, $like_q, $like_q, $like_q]);
    $types .= "ssss";
}

if ($category > 0) {
    $where_clauses[] = "j.category_id = ?";
    $params[] = $category;
    $types .= "i";
}

if (!empty($type)) {
    $where_clauses[] = "j.job_type = ?";
    $params[] = $type;
    $types .= "s";
}

if (!empty($experience)) {
    $where_clauses[] = "j.experience_level = ?";
    $params[] = $experience;
    $types .= "s";
}

$where_sql = " WHERE " . implode(" AND ", $where_clauses);

// Count total matching jobs
$count_sql = "SELECT COUNT(*) as total FROM jobs j" . $where_sql;
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_records = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages   = max(1, ceil($total_records / $limit));

// Fetch paginated jobs
$fetch_sql = "
    SELECT j.*, c.name as category_name
    FROM jobs j
    JOIN categories c ON j.category_id = c.id
    {$where_sql}
    ORDER BY j.created_at DESC
    LIMIT ?, ?
";

$fetch_params = array_merge($params, [$offset, $limit]);
$fetch_types  = $types . "ii";

$fetch_stmt = $conn->prepare($fetch_sql);
$fetch_stmt->bind_param($fetch_types, ...$fetch_params);
$fetch_stmt->execute();
$jobs = $fetch_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Render Jobs Card HTML
ob_start();
if (!empty($jobs)) {
    foreach ($jobs as $job) {
        ?>
        <div class="card-saas p-4 hover-lift">
            <div class="row align-items-center g-3">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge <?= get_job_type_badge($job['job_type']) ?>"><?= escape($job['job_type']) ?></span>
                        <span class="badge bg-light text-dark border"><?= escape($job['category_name']) ?></span>
                        <small class="text-muted ms-auto d-md-none"><?= time_ago($job['created_at']) ?></small>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">
                        <a href="<?= BASE_URL ?>/job-details.php?id=<?= $job['id'] ?>" class="text-dark text-decoration-none hover-primary">
                            <?= escape($job['title']) ?>
                        </a>
                    </h5>
                    <p class="text-primary fw-semibold small mb-2"><i class="bi bi-building me-1"></i><?= escape($job['company_name']) ?></p>
                    <div class="d-flex flex-wrap gap-3 text-muted small">
                        <span><i class="bi bi-geo-alt me-1"></i><?= escape($job['location']) ?></span>
                        <span><i class="bi bi-cash-stack me-1"></i><?= escape(formatJobSalary($job)) ?></span>
                        <span><i class="bi bi-briefcase me-1"></i><?= escape($job['experience_level']) ?> Level</span>
                    </div>
                </div>
                <div class="col-md-4 text-md-end pt-2 pt-md-0 border-top-md">
                    <small class="text-muted d-none d-md-block mb-2"><i class="bi bi-clock me-1"></i><?= time_ago($job['created_at']) ?></small>
                    <a href="<?= BASE_URL ?>/job-details.php?id=<?= $job['id'] ?>" class="btn btn-primary rounded-pill px-4 btn-sm fw-semibold">View & Apply</a>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="text-center py-5 card-saas">
        <i class="bi bi-search fs-1 text-secondary mb-2 d-block"></i>
        <h5 class="fw-bold mb-1">No Matching Jobs Found</h5>
        <p class="text-muted small">Try broadening your search keywords or resetting category filters.</p>
    </div>
    <?php
}
$jobs_html = ob_get_clean();

// Render Pagination HTML
ob_start();
if ($total_pages > 1) {
    ?>
    <nav aria-label="Job pagination">
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link rounded-start-pill px-3" href="#" data-page="<?= $page - 1 ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link px-3" href="#" data-page="<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                <a class="page-link rounded-end-pill px-3" href="#" data-page="<?= $page + 1 ?>">Next</a>
            </li>
        </ul>
    </nav>
    <?php
}
$pagination_html = ob_get_clean();

echo json_encode([
    'success'    => true,
    'html'       => $jobs_html,
    'pagination' => $pagination_html,
    'total'      => $total_records
]);
