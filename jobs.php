<?php
/**
 * CareerConnect - Jobs & Internships Search Catalog (Task 4 & 5 Requirement)
 */
$page_title = "Find Jobs & Internships | CareerConnect";
$extra_js = ['jobs.js'];
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';

// Initial PHP Fetch for Categories
$categories = [];
if ($conn) {
    $cat_res = $conn->query("SELECT * FROM categories ORDER BY name ASC");
    if ($cat_res) $categories = $cat_res->fetch_all(MYSQLI_ASSOC);
}

// Read query params from GET
$initial_q = sanitize($_GET['q'] ?? '');
$initial_category = (int)($_GET['category'] ?? 0);
$initial_type = sanitize($_GET['type'] ?? '');
?>

<div class="container py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="h3 font-heading fw-bold mb-1">Explore Career Opportunities</h1>
            <p class="text-muted mb-0">Search thousands of jobs and internships tailored to your skills.</p>
        </div>
        <div id="jobsCountText" class="badge badge-soft-primary fs-6 px-3 py-2 mt-2 mt-md-0">
            Searching jobs...
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card-saas p-4 sticky-top" style="top: 90px;">
                <h5 class="fw-bold mb-3"><i class="bi bi-funnel text-primary me-2"></i>Filters</h5>
                
                <!-- Keyword Search -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Search Keyword</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="jobSearchKeyword" class="form-control" placeholder="Title, skill, company..." value="<?= escape($initial_q) ?>">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Category</label>
                    <select id="jobCategoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $initial_category == $cat['id'] ? 'selected' : '' ?>>
                                <?= escape($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Job Type Filter -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Job Type</label>
                    <select id="jobTypeFilter" class="form-select">
                        <option value="">All Job Types</option>
                        <option value="Full-time" <?= $initial_type === 'Full-time' ? 'selected' : '' ?>>Full-time</option>
                        <option value="Internship" <?= $initial_type === 'Internship' ? 'selected' : '' ?>>Internship</option>
                        <option value="Remote" <?= $initial_type === 'Remote' ? 'selected' : '' ?>>Remote</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contract">Contract</option>
                    </select>
                </div>

                <!-- Experience Level Filter -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Experience Level</label>
                    <select id="jobExpFilter" class="form-select">
                        <option value="">All Levels</option>
                        <option value="Entry">Entry Level</option>
                        <option value="Mid">Mid Level</option>
                        <option value="Senior">Senior Level</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Job Cards List & Pagination -->
        <div class="col-lg-9">
            <div id="jobsListContainer" class="d-flex flex-column gap-3">
                <!-- AJAX populates job list here -->
            </div>

            <!-- Pagination Container -->
            <div id="jobsPaginationContainer" class="d-flex justify-content-center mt-4">
                <!-- AJAX populates pagination here -->
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
