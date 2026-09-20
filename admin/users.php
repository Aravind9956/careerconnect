<?php
/**
 * CareerConnect - Admin User Management CRUD (Task 3 & 4 Requirement)
 */
$page_title = "User Management | Admin | CareerConnect";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/navbar.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

require_role(ROLE_ADMIN);

// Toggle User Status / Delete
if (isset($_GET['action']) && isset($_GET['id'])) {
    $uid = (int)$_GET['id'];
    if ($_GET['action'] === 'toggle_status') {
        $conn->query("UPDATE users SET status = IF(status='active', 'suspended', 'active') WHERE id = {$uid} AND id != " . current_user_id());
        set_flash('success', 'User status updated.');
    } elseif ($_GET['action'] === 'delete') {
        $conn->query("DELETE FROM users WHERE id = {$uid} AND id != " . current_user_id());
        set_flash('success', 'User deleted from system.');
    }
    header("Location: " . BASE_URL . "/admin/users.php");
    exit;
}

// Fetch all users
$users = [];
if ($conn) {
    $res = $conn->query("
        SELECT u.*, r.name as role_name
        FROM users u
        JOIN roles r ON u.role_id = r.id
        ORDER BY u.created_at DESC
    ");
    if ($res) $users = $res->fetch_all(MYSQLI_ASSOC);
}
?>

<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Admin Nav -->
        <div class="col-lg-2">
            <div class="card-saas p-3">
                <div class="fw-bold text-dark px-3 py-2 mb-2 border-bottom">Admin Menu</div>
                <div class="nav flex-column nav-pills gap-1">
                    <a class="nav-link text-dark fw-medium" href="<?= BASE_URL ?>/admin/dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Overview</a>
                    <a class="nav-link active rounded-pill fw-semibold" href="<?= BASE_URL ?>/admin/users.php"><i class="bi bi-people me-2"></i>User Management</a>
                    <a class="nav-link rounded-pill text-dark fw-medium" href="<?= BASE_URL ?>/admin/jobs.php"><i class="bi bi-briefcase me-2"></i>Job Postings</a>
                    <a class="nav-link rounded-pill text-dark fw-medium" href="<?= BASE_URL ?>/admin/categories.php"><i class="bi bi-tags me-2"></i>Categories</a>
                    <a class="nav-link rounded-pill text-dark fw-medium" href="<?= BASE_URL ?>/admin/analytics.php"><i class="bi bi-graph-up-arrow me-2 text-info"></i>Analytics</a>
                </div>
            </div>
        </div>

        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 font-heading fw-bold mb-0">Platform User Management</h1>
            </div>

            <?= display_flash_alerts() ?>

            <div class="card-saas p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Registered</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <strong class="text-dark d-block"><?= escape($u['full_name']) ?></strong>
                                        <small class="text-muted">@<?= escape($u['username']) ?> &bull; <?= escape($u['email']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge <?= $u['role_id'] == ROLE_ADMIN ? 'bg-danger' : ($u['role_id'] == ROLE_RECRUITER ? 'bg-info text-dark' : 'bg-primary') ?> px-3 py-1">
                                            <?= escape($u['role_name']) ?>
                                        </span>
                                    </td>
                                    <td class="small text-muted"><?= escape($u['phone'] ?? 'N/A') ?></td>
                                    <td class="small text-muted"><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
                                    <td>
                                        <span class="badge <?= $u['status'] === 'active' ? 'bg-success' : 'bg-danger' ?> px-2.5 py-1">
                                            <?= ucfirst($u['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <?php if ($u['id'] != current_user_id()): ?>
                                            <a href="<?= BASE_URL ?>/admin/users.php?action=toggle_status&id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-warning rounded-pill px-3 me-1">
                                                <?= $u['status'] === 'active' ? 'Suspend' : 'Activate' ?>
                                            </a>
                                            <a href="<?= BASE_URL ?>/admin/users.php?action=delete&id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Permanently delete this user?');">Delete</a>
                                        <?php else: ?>
                                            <span class="badge bg-secondary px-3 py-1">Current You</span>
                                        <?php endif; ?>
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
