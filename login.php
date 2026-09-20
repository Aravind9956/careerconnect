<?php
/**
 * CareerConnect - User Authentication Login Page
 */
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';

if (is_logged_in()) {
    $user = current_user();
    if (($user['role_id'] ?? null) == ROLE_ADMIN) {
        header('Location: ' . BASE_URL . '/admin/dashboard.php');
        exit;
    }
    if (($user['role_id'] ?? null) == ROLE_RECRUITER) {
        header('Location: ' . BASE_URL . '/recruiter/dashboard.php');
        exit;
    }
    header('Location: ' . BASE_URL . '/user/dashboard.php');
    exit;
}

$page_title = 'Sign In | CareerConnect';
$extra_css = ['auth.css'];
$extra_js = ['auth.js'];
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="auth-wrapper">
    <div class="auth-card row g-0">
        <!-- Sidebar Branding -->
        <div class="col-lg-5 auth-sidebar d-none d-lg-flex">
            <div>
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="bg-white text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-briefcase-fill fs-5"></i>
                    </div>
                    <span class="fs-4 fw-bold text-white">CareerConnect</span>
                </div>
                <h3 class="fw-bold text-white mb-3">Welcome Back!</h3>
                <p class="text-white-50">Log in to track your job applications, explore new tech opportunities, or manage your candidate pipeline.</p>
            </div>
            
            <div class="p-3 bg-white bg-opacity-10 rounded-3">
                <small class="text-white-50 d-block">Demo Credentials:</small>
                <small class="text-white d-block mt-1"><strong>Seeker:</strong> john.doe@gmail.com / Password123!</small>
                <small class="text-white d-block"><strong>Recruiter:</strong> techcorp@careerconnect.com / Password123!</small>
                <small class="text-white d-block"><strong>Admin:</strong> admin@careerconnect.com / Password123!</small>
            </div>
        </div>

        <!-- Form Container -->
        <div class="col-lg-7 auth-form-container">
            <h2 class="fw-bold mb-1">Sign In to Your Account</h2>
            <p class="text-muted small mb-4">Enter your credentials below to access your dashboard.</p>

            <?= display_flash_alerts() ?>

            <form action="<?= BASE_URL ?>/auth/login-process.php" method="POST" id="loginForm">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Email Address or Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="text" name="login_identity" class="form-control py-2" placeholder="john.doe@gmail.com" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label small fw-semibold text-secondary mb-0">Password</label>
                        <a href="<?= BASE_URL ?>/forgot-password.php" class="small text-primary text-decoration-none">Forgot password?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" id="login_password" name="password" class="form-control py-2" placeholder="••••••••" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="login_password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                    <label class="form-check-label small text-muted" for="rememberMe">Remember this browser</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
                    Sign In &rarr;
                </button>

                <p class="text-center small text-muted mb-0">
                    Don't have an account? <a href="<?= BASE_URL ?>/register.php" class="text-primary fw-semibold text-decoration-none">Create an account</a>
                </p>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
