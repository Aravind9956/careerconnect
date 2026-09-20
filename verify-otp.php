<?php
/**
 * CareerConnect - Email OTP Verification Screen (Task 5 Requirement)
 */
$page_title = "Verify Email OTP | CareerConnect";
$extra_css = ['auth.css'];
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/csrf.php';

$pending_email = $_SESSION['pending_otp_email'] ?? '';
$simulated_code = $_SESSION['simulated_otp_code'] ?? '';

if (empty($pending_email)) {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}
?>

<div class="auth-wrapper">
    <div class="auth-card row g-0" style="max-width: 600px;">
        <div class="p-5 text-center w-100">
            <div class="brand-icon bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                <i class="bi bi-shield-lock-fill fs-2"></i>
            </div>
            
            <h3 class="fw-bold mb-2">Verify Your Email</h3>
            <p class="text-muted small mb-4">
                We sent a 6-digit verification code to<br>
                <strong class="text-dark"><?= escape($pending_email) ?></strong>
            </p>

            <?= display_flash_alerts() ?>

            <!-- Development / Evaluation OTP Helper Alert -->
            <?php if (!empty($simulated_code)): ?>
                <div class="alert alert-info border-info-subtle rounded-3 text-start small mb-4">
                    <i class="bi bi-info-circle-fill me-1"></i> <strong>Dev/Evaluation Gateway Notice:</strong><br>
                    Simulated Email OTP Code for local testing: <span class="badge bg-dark fs-6 px-3 py-1 text-monospace ms-1"><?= escape($simulated_code) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/auth/otp-process.php" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary">Enter 6-Digit OTP Code</label>
                    <input type="text" name="otp_code" class="form-control text-center fs-3 fw-bold tracking-widest py-2 rounded-3 mx-auto" style="max-width: 260px;" placeholder="123456" maxlength="6" required autofocus autocomplete="off">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold mb-3">
                    Verify Account & Login &rarr;
                </button>
            </form>

            <div class="pt-3 border-top d-flex align-items-center justify-content-between text-muted small">
                <span>Didn't receive the code?</span>
                <form action="<?= BASE_URL ?>/auth/otp-process.php" method="POST" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="resend">
                    <button type="submit" class="btn btn-link p-0 text-primary fw-semibold text-decoration-none small">Resend Code</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
