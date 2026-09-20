<?php
/**
 * CareerConnect - Main Landing Page (Task 1 & Task 4 Requirement)
 */
$page_title = "CareerConnect | Discover Opportunities. Build Your Career.";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/config/database.php';

// Fetch Categories with job counts
$categories = [];
$total_jobs = 0;
$total_users = 0;
$total_applications = 0;

if ($conn) {
    $cat_res = $conn->query("
        SELECT c.*, COUNT(j.id) as job_count 
        FROM categories c 
        LEFT JOIN jobs j ON c.id = j.category_id AND j.status = 'active'
        GROUP BY c.id 
        ORDER BY job_count DESC LIMIT 8
    ");
    if ($cat_res) $categories = $cat_res->fetch_all(MYSQLI_ASSOC);

    // Fetch Featured Active Jobs
    $featured_jobs_res = $conn->query("
        SELECT j.*, c.name as category_name 
        FROM jobs j 
        JOIN categories c ON j.category_id = c.id 
        WHERE j.status = 'active' 
        ORDER BY j.created_at DESC LIMIT 6
    ");
    $featured_jobs = $featured_jobs_res ? $featured_jobs_res->fetch_all(MYSQLI_ASSOC) : [];

    // Fetch Platform Metrics
    $jobs_count_res = $conn->query("SELECT COUNT(*) as count FROM jobs WHERE status = 'active'");
    if ($jobs_count_res) $total_jobs = $jobs_count_res->fetch_assoc()['count'];

    $users_count_res = $conn->query("SELECT COUNT(*) as count FROM users WHERE role_id = 3");
    if ($users_count_res) $total_users = $users_count_res->fetch_assoc()['count'];

    $apps_count_res = $conn->query("SELECT COUNT(*) as count FROM applications");
    if ($apps_count_res) $total_applications = $apps_count_res->fetch_assoc()['count'];
}
?>

<!-- 1. Hero Section -->
<section class="hero-gradient py-5 py-lg-6">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 text-center text-lg-start">
                <span class="badge badge-soft-primary mb-3 text-uppercase tracking-wider fw-bold">
                    <i class="bi bi-stars me-1"></i> ApexPlanet Internship Capstone
                </span>
                <h1 class="display-4 font-heading text-white fw-extrabold mb-3">
                    Discover Opportunities.<br>
                    <span class="text-primary">Build Your Career.</span>
                </h1>
                <p class="lead text-slate-300 mb-4 me-lg-4 fs-5">
                    Connect directly with top tech recruiters, explore internships, and track applications seamlessly in one unified platform.
                </p>

                <!-- Search Jobs Form -->
                <div class="search-card mb-4 text-start">
                    <form action="<?= BASE_URL ?>/jobs.php" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" name="q" class="form-control border-0 shadow-none ps-0" placeholder="Job title, skill, or keyword...">
                            </div>
                        </div>
                        <div class="col-md-4 border-start-md">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="location" class="form-control border-0 shadow-none ps-0" placeholder="City or Remote">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                                Search Jobs
                            </button>
                        </div>
                    </form>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start gap-3 text-slate-300 small">
                    <span class="fw-semibold text-white">Popular:</span>
                    <a href="<?= BASE_URL ?>/jobs.php?q=PHP" class="badge bg-secondary bg-opacity-25 text-white text-decoration-none px-2 py-1">PHP Developer</a>
                    <a href="<?= BASE_URL ?>/jobs.php?type=Internship" class="badge bg-secondary bg-opacity-25 text-white text-decoration-none px-2 py-1">Internships</a>
                    <a href="<?= BASE_URL ?>/jobs.php?q=Remote" class="badge bg-secondary bg-opacity-25 text-white text-decoration-none px-2 py-1">Remote Jobs</a>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-block">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80" alt="CareerConnect Platform" class="img-fluid rounded-4 shadow-lg border border-secondary border-opacity-25">
                    <div class="glass-card position-absolute bottom-0 start-0 m-3 p-3 rounded-3 shadow-lg max-w-xs">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                <i class="bi bi-check-lg fs-5"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">1,200+ Placements</h6>
                                <small class="text-muted">Verified candidate hires</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Statistics Counter Bar -->
<section class="py-4 bg-white border-bottom shadow-xs">
    <div class="container">
        <div class="row text-center g-3">
            <div class="col-md-4">
                <h3 class="fw-bold text-primary mb-0"><?= number_format(max($total_jobs, 6)) ?>+</h3>
                <p class="text-muted small mb-0">Active Job Postings</p>
            </div>
            <div class="col-md-4 border-start-md">
                <h3 class="fw-bold text-primary mb-0"><?= number_format(max($total_users, 15)) ?>+</h3>
                <p class="text-muted small mb-0">Registered Candidates</p>
            </div>
            <div class="col-md-4 border-start-md">
                <h3 class="fw-bold text-primary mb-0"><?= number_format(max($total_applications, 25)) ?>+</h3>
                <p class="text-muted small mb-0">Applications Submitted</p>
            </div>
        </div>
    </div>
</section>

<!-- 3. Popular Categories Section -->
<section class="py-5">
    <div class="container py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Popular Categories</h2>
                <p class="text-muted mb-0">Explore opportunities by your area of specialization.</p>
            </div>
            <a href="<?= BASE_URL ?>/jobs.php" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-semibold mt-2 mt-md-0">View All Categories &rarr;</a>
        </div>

        <div class="row g-3">
            <?php foreach ($categories as $cat): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="<?= BASE_URL ?>/jobs.php?category=<?= $cat['id'] ?>" class="text-decoration-none">
                        <div class="card-saas p-3 h-100 hover-lift d-flex align-items-center gap-3">
                            <div class="bg-primary-light text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi <?= escape($cat['icon']) ?> fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1"><?= escape($cat['name']) ?></h6>
                                <small class="text-muted"><?= $cat['job_count'] ?> Active Positions</small>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 4. Featured Jobs Section -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Featured Opportunities</h2>
                <p class="text-muted mb-0">Handpicked roles from top verified companies.</p>
            </div>
            <a href="<?= BASE_URL ?>/jobs.php" class="btn btn-primary rounded-pill px-4 btn-sm fw-semibold mt-2 mt-md-0">Browse All Jobs &rarr;</a>
        </div>

        <div class="row g-4">
            <?php if (!empty($featured_jobs)): ?>
                <?php foreach ($featured_jobs as $job): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card-saas p-4 h-100 hover-lift d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="badge <?= get_job_type_badge($job['job_type']) ?>"><?= escape($job['job_type']) ?></span>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i><?= time_ago($job['created_at']) ?></small>
                                </div>
                                <h5 class="fw-bold text-dark mb-2">
                                    <a href="<?= BASE_URL ?>/job-details.php?id=<?= $job['id'] ?>" class="text-dark text-decoration-none hover-primary">
                                        <?= escape($job['title']) ?>
                                    </a>
                                </h5>
                                <p class="text-primary fw-semibold small mb-2"><i class="bi bi-building me-1"></i><?= escape($job['company_name']) ?></p>
                                <p class="text-muted small mb-3"><i class="bi bi-geo-alt me-1"></i><?= escape($job['location']) ?></p>
                                <p class="text-secondary small mb-3 text-truncate-2"><?= escape(substr($job['description'], 0, 110)) ?>...</p>
                            </div>
                            <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                                <span class="fw-bold text-dark small"><?= escape(formatJobSalary($job)) ?></span>
                                <a href="<?= BASE_URL ?>/job-details.php?id=<?= $job['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Apply Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-4">
                    <p class="text-muted">No jobs posted yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 5. How It Works Section -->
<section id="how-it-works" class="py-5">
    <div class="container py-3">
        <div class="text-center max-w-2xl mx-auto mb-5">
            <h2 class="fw-bold mb-2">How CareerConnect Works</h2>
            <p class="text-muted">Four simple steps to launch your dream career or hire top candidates.</p>
        </div>

        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="card-saas p-4 h-100">
                    <div class="bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px;">1</div>
                    <h5 class="fw-bold mb-2">Create Account</h5>
                    <p class="text-muted small mb-0">Sign up as a Job Seeker or Recruiter in less than 2 minutes.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-saas p-4 h-100">
                    <div class="bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px;">2</div>
                    <h5 class="fw-bold mb-2">Build Profile</h5>
                    <p class="text-muted small mb-0">Add your skills, experience, education, and upload your resume PDF.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-saas p-4 h-100">
                    <div class="bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px;">3</div>
                    <h5 class="fw-bold mb-2">Search & Apply</h5>
                    <p class="text-muted small mb-0">Filter jobs by title, skill, remote option, or experience level.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-saas p-4 h-100">
                    <div class="bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px;">4</div>
                    <h5 class="fw-bold mb-2">Track & Get Hired</h5>
                    <p class="text-muted small mb-0">Monitor application status (Shortlisted/Selected) in real-time.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. Call To Action Banner -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container py-3">
        <h2 class="display-6 fw-bold text-white mb-3">Ready to Take the Next Step in Your Career?</h2>
        <p class="lead text-white-50 max-w-xl mx-auto mb-4">
            Join thousands of students and professionals discovering life-changing opportunities on CareerConnect.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= BASE_URL ?>/register.php" class="btn btn-light text-primary btn-lg rounded-pill px-5 fw-bold shadow-lg">Get Started Free</a>
            <a href="<?= BASE_URL ?>/register.php?type=recruiter" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-semibold">Post a Job</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
