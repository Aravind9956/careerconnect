<?php
/**
 * CareerConnect - 404 Not Found Custom Error Page
 */
http_response_code(404);
$page_title = "404 Page Not Found | CareerConnect";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="container py-5 text-center my-auto">
    <div class="max-w-md mx-auto py-5">
        <h1 class="display-1 font-heading fw-extrabold text-primary mb-0">404</h1>
        <h3 class="fw-bold text-dark mb-2">Oops! Page Not Found</h3>
        <p class="text-muted mb-4">The page or opportunity you are looking for might have been removed, renamed, or is temporarily unavailable.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary rounded-pill px-4">Back to Home</a>
            <a href="<?= BASE_URL ?>/jobs.php" class="btn btn-outline-primary rounded-pill px-4">Explore Jobs</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
