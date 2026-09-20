<?php
/**
 * CareerConnect - Forgot Password Recovery Page (Task 5 Requirement)
 */
$page_title = "Forgot Password | CareerConnect";
$extra_css = ['auth.css'];
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/csrf.php';

$step = $_GET['step'] ?? '1';
?>

<div class="auth-wrapper">
    <div class="auth-card row g-0" style="max-width: 550px;">
        <div class="p-5 w-100">
            <h3 class="fw-bold mb-2">Reset Password</h3>
            
            <?= display_flash_alerts() ?>

            <?php if ($step === '1'): ?>
                <p class="text-muted small mb-4">Enter your registered email address to receive a 6-digit password reset OTP code.</p>

                <form action="<?= BASE_URL ?>/auth/password-reset.php" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="step" value="1">
                    
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary">Registered Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control py-2" placeholder="john@example.com" required autofocus>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold mb-3">
                        Send Reset OTP &rarr;
                    </button>
                </form>
            <?php elseif ($step === '2'): ?>
                <p class="text-muted small mb-3">Enter the 6-digit OTP code sent to your email and your new password.</p>

                <?php if (!empty($_SESSION['simulated_reset_otp'])): ?>
                    <div class="alert alert-info small mb-3">
                        <i class="bi bi-info-circle me-1"></i> Simulated Reset OTP Code: <strong><?= escape($_SESSION['simulated_reset_otp']) ?></strong>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/auth/password-reset.php" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="step" value="2">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">6-Digit Reset OTP Code</label>
                        <input type="text" name="otp_code" class="form-control text-center fs-4 fw-bold tracking-wider" placeholder="123456" maxlength="6" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="••••••••" minlength="8" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary">Confirm New Password</label>
                        <input type="password" name="confirm_new_password" class="form-control" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold mb-3">
                        Update Password & Login &rarr;
                    </button>
                </form>
            <?php endif; ?>

            <p class="text-center small text-muted mb-0">
                Remembered your password? <a href="<?= BASE_URL ?>/login.php" class="text-primary fw-semibold text-decoration-none">Back to Sign In</a>
            </p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
