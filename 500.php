<?php
/**
 * CareerConnect - 500 Server Error Page
 */
http_response_code(500);
$page_title = "500 Server Error | CareerConnect";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="container py-5 text-center my-auto">
    <div class="max-w-md mx-auto py-5">
        <h1 class="display-1 font-heading fw-extrabold text-warning mb-0">500</h1>
        <h3 class="fw-bold text-dark mb-2">Internal Server Error</h3>
        <p class="text-muted mb-4">Something went wrong on our end. Our technical team has been notified.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary rounded-pill px-4">Back to Home</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
