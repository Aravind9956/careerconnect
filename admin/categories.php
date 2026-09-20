<?php
/**
 * CareerConnect - Job Categories CRUD (Task 4 Requirement)
 */
$page_title = "Manage Categories | Admin | CareerConnect";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

require_role(ROLE_ADMIN);

// Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    if (!verify_csrf_token()) {
        set_flash('error', 'Token invalid.');
        header("Location: " . BASE_URL . "/admin/categories.php");
        exit;
    }

    $name = sanitize($_POST['name'] ?? '');
    $slug = strtolower(str_replace(' ', '-', $name));
    $icon = sanitize($_POST['icon'] ?? 'bi-briefcase');
    $desc = sanitize($_POST['description'] ?? '');

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name, slug, icon, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $slug, $icon, $desc);
        $stmt->execute();
        set_flash('success', 'Category added!');
    }
    header("Location: " . BASE_URL . "/admin/categories.php");
    exit;
}

// Delete Category
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $cid = (int)$_GET['id'];
    $conn->query("DELETE FROM categories WHERE id = {$cid}");
    set_flash('success', 'Category deleted.');
    header("Location: " . BASE_URL . "/admin/categories.php");
    exit;
}

$categories = $conn->query("SELECT c.*, COUNT(j.id) as job_count FROM categories c LEFT JOIN jobs j ON c.id = j.category_id GROUP BY c.id ORDER BY c.name ASC")->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-lg-2">
            <div class="card-saas p-3">
                <div class="fw-bold text-dark px-3 py-2 mb-2 border-bottom">Admin Menu</div>
                <div class="nav flex-column nav-pills gap-1">
                    <a class="nav-link text-dark fw-medium" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Overview</a>
                    <a class="nav-link text-dark fw-medium" href="<?= BASE_URL ?>/admin/users.php"><i class="bi bi-people me-2"></i>User Management</a>
                    <a class="nav-link text-dark fw-medium" href="<?= BASE_URL ?>/admin/jobs.php"><i class="bi bi-briefcase me-2"></i>Job Postings</a>
                    <a class="nav-link active rounded-pill fw-semibold" href="<?= BASE_URL ?>/admin/categories.php"><i class="bi bi-tags me-2"></i>Categories</a>
                    <a class="nav-link rounded-pill text-dark fw-medium" href="<?= BASE_URL ?>/admin/analytics.php"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Analytics</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 font-heading fw-bold mb-0">Job Categories Management</h1>
                <button type="button" class="btn btn-primary rounded-pill px-4 btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-circle me-1"></i>Add Category
                </button>
            </div>

            <?= display_flash_alerts() ?>

            <div class="card-saas p-4">
                <div class="row g-3">
                    <?php foreach ($categories as $cat): ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="border rounded-3 p-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary-light text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi <?= escape($cat['icon']) ?> fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark"><?= escape($cat['name']) ?></h6>
                                        <small class="text-muted"><?= $cat['job_count'] ?> Positions</small>
                                    </div>
                                </div>
                                <a href="<?= BASE_URL ?>/admin/categories.php?action=delete&id=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-danger btn-icon rounded-circle" onclick="return confirm('Delete category?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold">New Job Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>/admin/categories.php" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Category Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. AI & Machine Learning" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Bootstrap Icon Identifier</label>
                        <input type="text" name="icon" class="form-control" value="bi-briefcase" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
