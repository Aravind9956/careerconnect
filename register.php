<?php
/**
 * CareerConnect - User Registration Page (Task 2 & Task 5 Requirement)
 */
$page_title = "Create Account | CareerConnect";
$extra_css = ['auth.css'];
$extra_js = ['auth.js'];
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/csrf.php';

$preset_role = sanitize($_GET['type'] ?? 'user');
?>

<div class="auth-wrapper">
    <div class="auth-card row g-0" style="max-width: 950px;">
        <!-- Sidebar Branding -->
        <div class="col-lg-5 auth-sidebar d-none d-lg-flex">
            <div>
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="bg-white text-primary rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-briefcase-fill fs-5"></i>
                    </div>
                    <span class="fs-4 fw-bold text-white">CareerConnect</span>
                </div>
                <h3 class="fw-bold text-white mb-3">Join CareerConnect</h3>
                <p class="text-white-50">Create your account to discover top jobs, apply with one click, or post positions to hire verified talent.</p>
            </div>
            <div class="p-3 bg-white bg-opacity-10 rounded-3 text-white small">
                <p class="mb-1"><i class="bi bi-shield-check me-2"></i>Email OTP Verification</p>
                <p class="mb-0"><i class="bi bi-file-earmark-lock me-2"></i>256-Bit Encrypted Passwords</p>
            </div>
        </div>

        <!-- Form Container -->
        <div class="col-lg-7 auth-form-container">
            <h2 class="fw-bold mb-1">Create an Account</h2>
            <p class="text-muted small mb-3">Please fill in your details to register.</p>

            <?= display_flash_alerts() ?>

            <form action="<?= BASE_URL ?>/auth/register-process.php" method="POST" id="registerForm">
                <?= csrf_field() ?>

                <!-- Account Type Selector Pills -->
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">I am joining as a:</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="role_id" id="role_user" value="<?= ROLE_USER ?>" <?= $preset_role !== 'recruiter' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary w-100 py-2 rounded-3 text-start small fw-semibold" for="role_user">
                                <i class="bi bi-person-fill me-1"></i> Job Seeker
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="role_id" id="role_recruiter" value="<?= ROLE_RECRUITER ?>" <?= $preset_role === 'recruiter' ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary w-100 py-2 rounded-3 text-start small fw-semibold" for="role_recruiter">
                                <i class="bi bi-building me-1"></i> Recruiter / Employer
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Full Name</label>
                        <input type="text" name="full_name" class="form-control" placeholder="John Doe" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="johndoe" required>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Email Address</label>
                        <input type="email" id="reg_email" name="email" class="form-control" placeholder="john@example.com" required>
                        <div id="emailFeedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" placeholder="+1 555-0199">
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Password</label>
                        <div class="input-group">
                            <input type="password" id="reg_password" name="password" class="form-control" placeholder="••••••••" minlength="8" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="reg_password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Confirm Password</label>
                        <input type="password" id="reg_confirm_password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                        <div id="confirmFeedback"></div>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" name="terms" class="form-check-input" id="terms" required>
                    <label class="form-check-label small text-muted" for="terms">
                        I agree to the <a href="#" class="text-primary text-decoration-none">Terms of Service</a> and <a href="#" class="text-primary text-decoration-none">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3">
                    Create Account & Send OTP &rarr;
                </button>

                <p class="text-center small text-muted mb-0">
                    Already registered? <a href="<?= BASE_URL ?>/login.php" class="text-primary fw-semibold text-decoration-none">Sign In</a>
                </p>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
