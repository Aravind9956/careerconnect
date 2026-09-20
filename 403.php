<?php
/**
 * CareerConnect - 403 Forbidden Access Error Page
 */
http_response_code(403);
$page_title = "403 Access Forbidden | CareerConnect";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="container py-5 text-center my-auto">
    <div class="max-w-md mx-auto py-5">
        <h1 class="display-1 font-heading fw-extrabold text-danger mb-0">403</h1>
        <h3 class="fw-bold text-dark mb-2">Access Denied</h3>
        <p class="text-muted mb-4">You do not have authorization to view this resource. Please sign in with an account that has permission.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary rounded-pill px-4">Back to Home</a>
            <a href="<?= BASE_URL ?>/login.php" class="btn btn-outline-primary rounded-pill px-4">Switch Account</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
